<?php

namespace App\Services\Dosen;

use App\Models\PendaftaranSidang;
use App\Models\Sidang;
use App\Models\TugasAkhir;
use App\Models\User;
use App\Models\PeranDosenTa;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class PersetujuanSidangService
 * Menangani semua logika bisnis yang terkait dengan proses persetujuan dan penolakan
 * pendaftaran sidang oleh dosen pembimbing.
 */
class PersetujuanSidangService
{
    /**
     * Menangani proses persetujuan pendaftaran sidang oleh dosen.
     */
    public function handleApproval(int $pendaftaranId, User $user): void
    {
        DB::transaction(function () use ($pendaftaranId, $user) {
            $pendaftaran = PendaftaranSidang::with('tugasAkhir')->findOrFail($pendaftaranId);

            $this->authorizeIsPembimbing($pendaftaran->tugasAkhir, $user);

            $this->updateApprovalStatus($pendaftaran, $user);

            $this->checkAndCreateSidang($pendaftaran);
        });
    }

    /**
     * Menangani proses penolakan pendaftaran sidang oleh dosen.
     */
    public function handleRejection(int $pendaftaranId, User $user, string $catatan): void
    {
        DB::transaction(function () use ($pendaftaranId, $user, $catatan) {
            $pendaftaran = PendaftaranSidang::with('tugasAkhir')->findOrFail($pendaftaranId);

            $this->authorizeIsPembimbing($pendaftaran->tugasAkhir, $user);

            $peranDosen = $this->getPeranDosen($pendaftaran->tugas_akhir_id, $user->dosen->id);

            if ($peranDosen) {
                if ($peranDosen->peran === 'pembimbing1') {
                    // Kolom ini ada di skema 'pendaftaran_sidang' Anda
                    $pendaftaran->status_pembimbing_1 = 'ditolak';
                    $pendaftaran->catatan_pembimbing_1 = $catatan;
                } elseif ($peranDosen->peran === 'pembimbing2') {
                    // Kolom ini ada di skema 'pendaftaran_sidang' Anda
                    $pendaftaran->status_pembimbing_2 = 'ditolak';
                    $pendaftaran->catatan_pembimbing_2 = $catatan;
                }
            }

            // Kolom ini ada di skema 'pendaftaran_sidang' Anda
            $pendaftaran->status_verifikasi = 'ditolak';
            $pendaftaran->save();
        });
    }

    /**
     * Otorisasi: Memastikan user adalah pembimbing yang sah berdasarkan tabel pivot.
     */
    private function authorizeIsPembimbing(TugasAkhir $tugasAkhir, User $user): void
    {
        $dosenId = $user->dosen?->id;

        $isPembimbing = $tugasAkhir->peranDosenTa()
            ->where('dosen_id', $dosenId)
            ->whereIn('peran', ['pembimbing1', 'pembimbing2'])
            ->exists();

        if (!$isPembimbing) {
            throw new AuthorizationException('Aksi tidak diizinkan. Anda bukan pembimbing yang berwenang untuk tugas akhir ini.');
        }
    }

    /**
     * Mengupdate status persetujuan berdasarkan peran dosen dari tabel pivot.
     */
    private function updateApprovalStatus(PendaftaranSidang $pendaftaran, User $user): void
    {
        $peranDosen = $this->getPeranDosen($pendaftaran->tugas_akhir_id, $user->dosen->id);

        if ($peranDosen) {
            if ($peranDosen->peran === 'pembimbing1') {
                $pendaftaran->status_pembimbing_1 = 'disetujui';
            } elseif ($peranDosen->peran === 'pembimbing2') {
                $pendaftaran->status_pembimbing_2 = 'disetujui';
            }
        }
        $pendaftaran->save();
    }

    /**
     * Helper untuk mendapatkan peran dosen dari tabel pivot.
     */
    private function getPeranDosen(int $tugasAkhirId, int $dosenId): ?PeranDosenTa
    {
        return PeranDosenTa::where('tugas_akhir_id', $tugasAkhirId)
            ->where('dosen_id', $dosenId)
            ->first();
    }

    /**
     * Memeriksa jika kedua pembimbing telah setuju dan membuat entri sidang.
     */
    private function checkAndCreateSidang(PendaftaranSidang $pendaftaran): void
    {
        $pendaftaran->refresh();

        if ($pendaftaran->status_pembimbing_1 === 'disetujui' && $pendaftaran->status_pembimbing_2 === 'disetujui') {

            Sidang::updateOrCreate(
                // Kolom ini ada di skema 'sidang' Anda
                ['pendaftaran_sidang_id' => $pendaftaran->id],
                [
                    // Kolom ini ada di skema 'sidang' Anda
                    'tugas_akhir_id' => $pendaftaran->tugas_akhir_id,
                    'status_hasil' => 'dijadwalkan',
                ]
            );

            $pendaftaran->status_verifikasi = 'disetujui';
            $pendaftaran->save();
        }
    }
}
