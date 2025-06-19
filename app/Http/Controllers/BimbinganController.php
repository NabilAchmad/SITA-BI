<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function dashboard()
    {
        // Logika untuk menampilkan dashboard bimbingan
        return view('mahasiswa.bimbingan.dashboard.dashboard');
    }

    public function ajukanJadwal()
    {
        // Logika untuk menampilkan form ajukan jadwal bimbingan
        return view('mahasiswa.bimbingan.views.ajukanBimbingan');
    }

    public function jadwalBimbingan()
    {
        // Logika untuk menampilkan jadwal bimbingan
        return view('mahasiswa.bimbingan.views.lihatJadwal');
    }

    public function ubahJadwal(){
        return view('mahasiswa.bimbingan.views.perubahanJadwal');
    }
}
