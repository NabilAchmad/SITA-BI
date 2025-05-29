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
    public function belumMulai()
{
    // logic untuk menampilkan mahasiswa yang belum memulai bimbingan
    return view('admin.bimbingan.crud-bimbingan.belum-mulai');
}

public function sedangBerlangsung()
{
    // logic untuk menampilkan mahasiswa yang sedang bimbingan
    return view('admin.bimbingan.crud-bimbingan.sedang-berlangsung');
}

public function menungguReview()
{
    // logic untuk menampilkan mahasiswa yang menunggu review
    return view('admin.bimbingan.crud-bimbingan.menunggu-review');
}

public function selesai()
{
    // logic untuk menampilkan mahasiswa yang sudah selesai bimbingan
    return view('admin.bimbingan.crud-bimbingan.selesai');
}

}
