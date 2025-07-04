<?php

namespace App\Services\Kaprodi;

use App\Models\TugasAkhir;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ValidasiService
{
    /**
     * Mengambil semua daftar tugas akhir untuk halaman validasi,
     * otomatis terfilter berdasarkan prodi Kaprodi yang login.
     */
    public function getValidationLists(): array
    {
        $user = Auth::user();
        $programStudi = null;

        if ($user->hasRole('kaprodi-d3')) {
            $programStudi = 'D3';
        } elseif ($user->hasRole('kaprodi-d4')) {
            $programStudi = 'D4';
        }

        $baseQuery = TugasAkhir::with('mahasiswa.user');

        if ($programStudi) {
            $baseQuery->whereHas('mahasiswa', function ($query) use ($programStudi) {
                $query->where('prodi', $programStudi);
            });
        }

        return [
            'tugasAkhirMenunggu' => (clone $baseQuery)->where('status', TugasAkhir::STATUS_DIAJUKAN)->latest()->get(),
            'tugasAkhirDiterima' => (clone $baseQuery)->where('status', TugasAkhir::STATUS_DISETUJUI)->latest()->get(),
            'tugasAkhirDitolak'  => (clone $baseQuery)->where('status', TugasAkhir::STATUS_DITOLAK)->latest()->get(),
        ];
    }

    /**
     * Mengambil detail yang diperlukan untuk modal.
     */
    public function getValidationDetails(TugasAkhir $tugasAkhir): array
    {
        $tugasAkhir->load('mahasiswa.user', 'disetujuiOleh', 'ditolakOleh');

        $details = [
            'nama' => $tugasAkhir->mahasiswa->user->name,
            'nim' => $tugasAkhir->mahasiswa->nim,
            'prodi' => $tugasAkhir->mahasiswa->prodi,
            'judul' => $tugasAkhir->judul,
            'status_label' => strtoupper($tugasAkhir->status),
            'actionable' => $tugasAkhir->status === TugasAkhir::STATUS_DIAJUKAN,
            'similar' => $tugasAkhir->status === TugasAkhir::STATUS_DIAJUKAN ? $this->findSimilarTitles($tugasAkhir) : [],
            'disetujui_oleh' => optional($tugasAkhir->disetujuiOleh)->name,
            'tanggal_disetujui' => optional($tugasAkhir->tanggal_persetujuan)->format('d F Y'),
            'alasan_penolakan' => $tugasAkhir->alasan_penolakan,
            'ditolak_oleh' => optional($tugasAkhir->ditolakOleh)->name,
            'tanggal_ditolak' => $tugasAkhir->status === TugasAkhir::STATUS_DITOLAK ? $tugasAkhir->updated_at->format('d F Y') : null,
        ];

        return $details;
    }

    /**
     * Menyetujui pengajuan tugas akhir.
     */
    public function approveTugasAkhir(TugasAkhir $tugasAkhir): bool
    {
        return $tugasAkhir->update([
            'status' => TugasAkhir::STATUS_DISETUJUI,
            'tanggal_persetujuan' => now(),
            'disetujui_oleh' => Auth::id(),
            'alasan_penolakan' => null, // Bersihkan alasan penolakan jika ada
        ]);
    }

    /**
     * Menolak pengajuan tugas akhir.
     */
    public function rejectTugasAkhir(TugasAkhir $tugasAkhir, string $alasan): bool
    {
        return $tugasAkhir->update([
            'status' => TugasAkhir::STATUS_DITOLAK,
            'alasan_penolakan' => $alasan,
            'ditolak_oleh' => Auth::id(),
        ]);
    }

    /**
     * Mencari judul yang mirip.
     */
    private function findSimilarTitles(TugasAkhir $target, int $threshold = 70): Collection
    {
        $existingTitles = TugasAkhir::where('id', '!=', $target->id)
            ->where('status', TugasAkhir::STATUS_DISETUJUI)
            ->pluck('judul');

        return $existingTitles->mapWithKeys(function ($title) use ($target, $threshold) {
            similar_text(strtolower($target->judul), strtolower($title), $percentage);
            return $percentage >= $threshold ? [$title => round($percentage)] : [];
        })->sortDesc();
    }
}
