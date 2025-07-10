<?php

namespace App\Policies;

use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TugasAkhirPolicy
{
    /**
     * Otorisasi untuk melihat halaman validasi (apakah menu boleh muncul).
     */
    public function viewAny(User $user): bool
    {
        // ✅ PERBAIKAN: Mengambil ulang data user yang "segar" untuk memastikan peran terbaca.
        $freshUser = User::find($user->id);
        return $freshUser && $freshUser->hasAnyRole(['admin', 'kajur', 'kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * ✅ PERBAIKAN: Otorisasi untuk MELIHAT DETAIL dan CEK KEMIRIPAN.
     * Sekarang memanggil helper yang sama untuk konsistensi.
     */
    public function view(User $user, TugasAkhir $tugasAkhir): bool
    {
        // Panggil metode helper dengan aturan yang mengizinkan admin dan kajur tanpa syarat.
        return $this->isAuthorizedForAction($user, $tugasAkhir, ['admin', 'kajur'], ['kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * ✅ PERBAIKAN: Otorisasi untuk MENYETUJUI, MENOLAK, atau MENGEDIT PEMBIMBING.
     * Logika ini sekarang benar-benar tahan banting terhadap masalah sesi.
     */
    public function update(User $user, TugasAkhir $tugasAkhir): bool
    {
        // Panggil metode helper dengan aturan yang lebih ketat (Admin tidak bisa update).
        return $this->isAuthorizedForAction($user, $tugasAkhir, ['kajur'], ['kaprodi-d3', 'kaprodi-d4']);
    }

    /**
     * Otorisasi untuk Cek Kemiripan.
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
        // Menggunakan kembali logika 'update' karena hak aksesnya sama.
        return $this->update($user, $tugasAkhir);
    }

    /**
     * Otorisasi untuk mengembalikan data dari soft delete.
     */
    public function restore(User $user, TugasAkhir $tugasAkhir): bool
    {
        $freshUser = User::find($user->id);
        return $freshUser && $freshUser->hasAnyRole(['admin', 'kajur']);
    }

    /**
     * Otorisasi untuk menghapus data secara permanen.
     */
    public function forceDelete(User $user, TugasAkhir $tugasAkhir): bool
    {
        $freshUser = User::find($user->id);
        return $freshUser && $freshUser->hasRole('kajur');
    }

    /**
     * ======================================================================
     * METODE HELPER "TAHAN BANTING" (INTI DARI PERBAIKAN)
     * ======================================================================
     * Metode ini tidak mempercayai objek User dari sesi. Ia akan selalu
     * mengambil data yang segar dari database untuk memastikan peran terbaca.
     *
     * @param User $staleUser Objek user dari sesi yang mungkin usang.
     * @param TugasAkhir $tugasAkhir Model yang akan diotorisasi.
     * @param array $unconditionalRoles Peran yang diizinkan tanpa syarat (e.g., 'kajur').
     * @param array $conditionalRoles Peran yang diizinkan dengan syarat prodi (e.g., 'kaprodi-d3').
     * @return bool
     */
    private function isAuthorizedForAction(User $staleUser, TugasAkhir $tugasAkhir, array $unconditionalRoles, array $conditionalRoles): bool
    {
        // Langkah 1: Ambil objek User yang "segar" dari database, lengkap dengan perannya.
        $freshUser = User::with('roles')->find($staleUser->id);
        if (!$freshUser) {
            return false;
        }

        // Langkah 2: Periksa apakah pengguna memiliki peran yang diizinkan tanpa syarat.
        if ($freshUser->hasAnyRole($unconditionalRoles)) {
            return true;
        }

        // Langkah 3: Pastikan relasi mahasiswa dimuat untuk pengecekan prodi.
        // `loadMissing` lebih efisien karena hanya memuat jika belum ada.
        $tugasAkhir->loadMissing('mahasiswa');
        if (!$tugasAkhir->mahasiswa?->prodi) {
            return false;
        }

        // Langkah 4: Periksa peran kondisional (Kaprodi) dengan perbandingan yang aman.
        $mahasiswaProdi = strtolower(trim($tugasAkhir->mahasiswa->prodi));

        foreach ($conditionalRoles as $role) {
            // Contoh: jika peran adalah 'kaprodi-d4' dan prodi mahasiswa adalah 'd4'
            if ($freshUser->hasRole($role) && str_ends_with($role, $mahasiswaProdi)) {
                return true;
            }
        }

        // Jika tidak ada kondisi yang cocok, tolak akses.
        return false;
    }
}
