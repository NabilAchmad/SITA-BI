<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function dashboard()
    {
        // Logika untuk menampilkan daftar bimbingan
        return view('admin.bimbingan.dashboard.dashboard');

    }
    public function ajukanJadwal()
{
    // logic untuk menampilkan mahasiswa yang belum memulai bimbingan
    return view('admin.bimbingan.crud-bimbingan.ajukan-jadwal');
}

    public function lihatBimbingan()
{
    // logic untuk menampilkan mahasiswa yang sedang bimbingan
    return view('admin.bimbingan.crud-bimbingan.lihat-bimbingan');
}

    public function ajukanPerubahan()
{
    // logic untuk menampilkan mahasiswa yang menunggu review
    return view('admin.bimbingan.crud-bimbingan.ajukan-perubahan');
}


}
