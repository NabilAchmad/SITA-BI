<?php

namespace App\Services\Kaprodi;

use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidasiService
{
    /**
     * Mengambil daftar tugas akhir yang menunggu validasi, dengan filter.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTugasAkhirForValidation(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // PERBAIKAN: Gunakan scope model untuk query yang lebih bersih.
        // Anda perlu menambahkan scope ini di model TugasAkhir.
        $query = TugasAkhir::awaitingValidation();

        // Terapkan filter prodi
        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi', $request->prodi));
        }

        // Terapkan filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Eager load relasi untuk menghindari N+1 query problem di view.
        return $query->with('mahasiswa.user')
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Agar filter tetap ada saat paginasi
    }

    /**
     * Mengambil semua data yang diperlukan untuk modal detail validasi.
     *
     * @param TugasAkhir $tugasAkhir
     * @return array
     */
    public function getValidationDetails(TugasAkhir $tugasAkhir): array
    {
        $tugasAkhir->load('mahasiswa.user');

        $base = [
            'nama' => $tugasAkhir->mahasiswa->user->name,
            'nim' => $tugasAkhir->mahasiswa->nim,
            'prodi' => $tugasAkhir->mahasiswa->prodi === 'd3' ? 'D3 Bahasa Inggris' : 'D4 Bahasa Inggris',
            'judul' => $tugasAkhir->judul,
            'status' => $tugasAkhir->status,
            'status_label' => strtoupper($tugasAkhir->status),
        ];

        switch ($tugasAkhir->status) {
            case TugasAkhir::STATUS_DIAJUKAN:
                return array_merge($base, [
                    'similar' => $this->findSimilarTitles($tugasAkhir),
                    'actionable' => true,
                ]);

            case TugasAkhir::STATUS_DISETUJUI:
                return array_merge($base, [
                    'disetujui_oleh' => optional($tugasAkhir->disetujui_oleh)->name ?? 'Kaprodi',
                    'tanggal_disetujui' => $tugasAkhir->updated_at->format('d-m-Y'),
                    'similar' => [], // Tidak perlu cari similar
                    'actionable' => false,
                ]);

            case TugasAkhir::STATUS_DITOLAK:
                return array_merge($base, [
                    'alasan_penolakan' => $tugasAkhir->alasan_penolakan ?? '-',
                    'ditolak_oleh' => optional($tugasAkhir->ditolak_oleh)->name ?? 'Kaprodi',
                    'tanggal_ditolak' => $tugasAkhir->updated_at->format('d-m-Y'),
                    'similar' => [], // Tidak perlu cari similar
                    'actionable' => false,
                ]);

            default:
                return array_merge($base, [
                    'keterangan' => 'Status tidak dikenali.',
                    'similar' => [],
                    'actionable' => false,
                ]);
        }
    }

    /**
     * Menyetujui sebuah pengajuan tugas akhir.
     *
     * @param TugasAkhir $tugasAkhir
     * @return bool
     */
    public function approveTugasAkhir(TugasAkhir $tugasAkhir): bool
    {
        return $tugasAkhir->update([
            'status' => TugasAkhir::STATUS_DISETUJUI,
            'tanggal_persetujuan' => now(), // PERBAIKAN: Catat tanggal disetujui
            'disetujui_oleh' => Auth::id() // Simpan ID user yang menyetujui
        ]);
    }

    /**
     * Menolak pengajuan dan memberikan catatan revisi.
     *
     * @param TugasAkhir $tugasAkhir
     * @param string $catatanRevisi
     * @return bool
     */
    public function rejectTugasAkhir(TugasAkhir $tugasAkhir, string $catatanRevisi): bool
    {
        // Penggunaan DB::transaction sudah sangat baik.
        return DB::transaction(function () use ($tugasAkhir, $catatanRevisi) {
            $tugasAkhir->update([
                'status' => TugasAkhir::STATUS_DITOLAK, // Gunakan status DITOLAK
                'alasan_penolakan' => $catatanRevisi, // Simpan alasan di kolom komentar utama
                'ditolak_oleh' => Auth::id(), // Simpan ID user yang menolak
            ]);

            // Jika Anda ingin sistem revisi formal, logika ini bisa disimpan.
            // Namun untuk penolakan judul, seringkali cukup dengan komentar.
            // $tugasAkhir->revisiTa()->create([...]);

            return true;
        });
    }

    /**
     * PERBAIKAN: Fungsi private untuk mencari judul yang mirip.
     * Logika ini sekarang terpusat di dalam service.
     *
     * @param TugasAkhir $targetTugasAkhir
     * @param int $threshold Persentase kemiripan minimum (default 75%)
     * @return Collection
     */
    private function findSimilarTitles(TugasAkhir $targetTugasAkhir, int $threshold = 75): Collection
    {
        // Ambil semua judul lain yang relevan (sudah ada/disetujui)
        $existingTitles = TugasAkhir::where('id', '!=', $targetTugasAkhir->id)
            ->whereIn('status', [TugasAkhir::STATUS_DISETUJUI, TugasAkhir::STATUS_SELESAI])
            ->pluck('judul');

        $similarTitles = collect();
        $targetTitle = strtolower($targetTugasAkhir->judul);

        foreach ($existingTitles as $title) {
            similar_text($targetTitle, strtolower($title), $percentage);

            if ($percentage >= $threshold) {
                // Tambahkan judul yang mirip beserta persentasenya
                $similarTitles->push($title . " (" . round($percentage) . "% mirip)");
            }
        }

        return $similarTitles;
    }
}
