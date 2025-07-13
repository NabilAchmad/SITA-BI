<?php

namespace App\Policies;

use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TugasAkhirPolicy
{
    /**
     * Otorisasi untuk melihat menu validasi.
     */
    public function viewAny(User $user): bool
    {
        return $this->isUserWithRole($user, ['admin', 'kajur', 'kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * Otorisasi untuk MELIHAT DETAIL dan CEK KEMIRIPAN.
     */
    public function view(User $user, TugasAkhir $tugasAkhir): bool
    {
        return $this->isAuthorizedForAction(
            $user,
            $tugasAkhir,
            ['admin', 'kajur'],          // Peran tanpa syarat
            ['kaprodi-d3', 'kaprodi-d4'], // Peran dengan syarat prodi
            true                         // Aktifkan pengecekan pembimbing
        );
    }

    /**
     * Otorisasi untuk MENYETUJUI, MENOLAK, atau MENGEDIT PEMBIMBING.
     */
    public function update(User $user, TugasAkhir $tugasAkhir): bool
    {
        return $this->isAuthorizedForAction(
            $user,
            $tugasAkhir,
            ['kajur'],
            ['kaprodi-d3', 'kaprodi-d4'],
            false // Pembimbing tidak boleh melakukan update validasi
        );
    }

    /**
     * Otorisasi untuk Cek Kemiripan (alias dari 'view').
     */
    public function cekKemiripan(User $user, TugasAkhir $tugasAkhir): bool
    {
        return $this->view($user, $tugasAkhir);
    }

    /**
     * Otorisasi untuk melakukan soft delete.
     */
    public function delete(User $user, TugasAkhir $tugasAkhir): bool
    {
        return $this->update($user, $tugasAkhir);
    }

    /**
     * Otorisasi untuk mengembalikan data dari soft delete.
     */
    public function restore(User $user, TugasAkhir $tugasAkhir): bool
    {
        return $this->isUserWithRole($user, ['admin', 'kajur']);
    }

    /**
     * Otorisasi untuk menghapus data secara permanen.
     */
    public function forceDelete(User $user, TugasAkhir $tugasAkhir): bool
    {
        return $this->isUserWithRole($user, ['kajur']);
    }

    // ======================================================================
    // METODE HELPER
    // ======================================================================

    private function isUserWithRole(User $staleUser, array $roles): bool
    {
        $freshUser = User::find($staleUser->id);
        return $freshUser && $freshUser->hasAnyRole($roles);
    }

    private function isAuthorizedForAction(
        User $staleUser,
        TugasAkhir $tugasAkhir,
        array $unconditionalRoles,
        array $conditionalRoles,
        bool $checkPembimbing = false
    ): bool {
        $freshUser = User::with('roles')->find($staleUser->id);
        if (!$freshUser) {
            return false;
        }

        if ($checkPembimbing) {
            // ✅ PERBAIKAN: Panggil accessor secara langsung.
            // Tidak perlu load/loadMissing karena ini bukan relasi.
            // Accessor akan mengambil data dari relasi 'dosenPembimbing' yang sudah di-load oleh Service.
            $pembimbing1 = $tugasAkhir->pembimbing_satu;
            $pembimbing2 = $tugasAkhir->pembimbing_dua;

            if (($pembimbing1 && $freshUser->id === $pembimbing1->user_id) || ($pembimbing2 && $freshUser->id === $pembimbing2->user_id)) {
                return true;
            }
        }

        if ($freshUser->hasAnyRole($unconditionalRoles)) {
            return true;
        }

        // ✅ PERBAIKAN: Pastikan relasi mahasiswa di-load jika belum ada,
        // karena ini tidak terkait dengan accessor pembimbing.
        $tugasAkhir->loadMissing('mahasiswa');
        if (!$tugasAkhir->mahasiswa?->prodi) {
            return false;
        }

        $mahasiswaProdi = strtolower(trim($tugasAkhir->mahasiswa->prodi));
        foreach ($conditionalRoles as $role) {
            if ($freshUser->hasRole($role) && str_ends_with($role, $mahasiswaProdi)) {
                return true;
            }
        }

        return false;
    }
}
