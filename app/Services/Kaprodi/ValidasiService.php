<?php

namespace App\Services\Kaprodi;

use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidasiService
{
    /**
     * Mengambil daftar tugas akhir yang menunggu validasi, dengan filter.
     */
    public function getTugasAkhirForValidation(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Menggunakan scope 'awaitingValidation' yang bisa kita tambahkan di model TugasAkhir
        $query = TugasAkhir::query()->whereIn('status', [TugasAkhir::STATUS_DIAJUKAN, TugasAkhir::STATUS_REVISI]);

        // Terapkan filter jika ada
        if ($request->filled('search')) {
            $query->whereHas('mahasiswa.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('prodi')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        return $query->with('mahasiswa.user')->latest()->paginate(10);
    }

    /**
     * Menyetujui sebuah pengajuan tugas akhir.
     */
    public function approveTugasAkhir(TugasAkhir $tugasAkhir): bool
    {
        return $tugasAkhir->update(['status' => TugasAkhir::STATUS_DISETUJUI]);
    }

    /**
     * Menolak sebuah pengajuan tugas akhir dan memberikan catatan revisi.
     */
    public function rejectTugasAkhir(TugasAkhir $tugasAkhir, string $catatanRevisi): bool
    {
        return DB::transaction(function () use ($tugasAkhir, $catatanRevisi) {
            // Ubah status tugas akhir menjadi 'revisi'
            $tugasAkhir->update(['status' => TugasAkhir::STATUS_REVISI]);

            // Buat catatan revisi baru
            $tugasAkhir->revisiTa()->create([
                'catatan' => $catatanRevisi,
                'pemberi_revisi_id' => Auth::id(), // Simpan ID user Kaprodi
                'status' => 'pending'
            ]);

            return true;
        });
    }
}
