<?php

namespace App\Policies;

use App\Models\TugasAkhir;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TugasAkhirPolicy
{
    use HandlesAuthorization;

    /**
     * Menentukan apakah user dapat melihat detail tugas akhir.
     * Kaprodi hanya boleh melihat TA dari prodinya sendiri.
     */
    public function view(User $user, TugasAkhir $tugasAkhir): bool
    {
        // Ambil prodi dari mahasiswa yang mengajukan TA
        $prodiMahasiswa = $tugasAkhir->mahasiswa?->prodi;

        // Jika user adalah Kaprodi D3, dia hanya boleh melihat TA mahasiswa D3
        if ($user->hasRole('kaprodi-d3') && $prodiMahasiswa === 'd3') {
            return true;
        }

        // Jika user adalah Kaprodi D4, dia hanya boleh melihat TA mahasiswa D4
        if ($user->hasRole('kaprodi-d4') && $prodiMahasiswa === 'd4') {
            return true;
        }

        // Jika tidak cocok, tolak akses
        return false;
    }

    /**
     * Menentukan apakah user dapat menyetujui atau menolak tugas akhir.
     * Logikanya sama dengan 'view'.
     */
    public function update(User $user, TugasAkhir $tugasAkhir): bool
    {
        // Hanya bisa diupdate jika statusnya masih 'diajukan'
        if ($tugasAkhir->status !== 'diajukan') {
            return false;
        }

        // Gunakan logika yang sama dengan method 'view'
        return $this->view($user, $tugasAkhir);
    }
}
