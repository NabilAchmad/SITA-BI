<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\TugasAkhir;

class MahasiswaService
{
    public static function getMahasiswaAktif($user)
    {
        return $user->mahasiswa;
    }

    public static function getTugasAkhirAktif(Mahasiswa $mahasiswa)
    {
        return $mahasiswa->tugasAkhir;
    }
}
