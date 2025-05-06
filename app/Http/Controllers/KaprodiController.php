<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KaprodiController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('kaprodi.dashboard');
    }

    // Jadwal Sidang
    public function showJadwal()
    {
        return view('kaprodi.jadwal.readJadwal');
    }

    // Acc Judul Tugas Akhir
    public function showAccJudulTA()
    {
        return view('kaprodi.judulTA.AccJudulTA');
    }

    // Nilai Sidang
    public function showNilaiSidang()
    {
        return view('kaprodi.sidang.readSidang');
    }

    // Create Sidang
    public function createSidang()
    {
        return view('kaprodi.sidang.createSidang');
    }

    // Pengumuman
    public function showPengumuman()
    {
        return view('kaprodi.Pengumuman.pengumuman');
    }
}
