<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasAkhirController;

Route::get('/', function () {
    return view('home/homepage');
});

// Dashboard Mahasiswa
Route::get('/mahasiswa', function () {
    return view('mahasiswa/dashboard');
});

// Ajukan Tugas Akhir
Route::get('/mahasiswa/ajukan-tugas-akhir', function () {
    return view('mahasiswa/TugasAkhir/form');
});

// Menampilkan form ajukan berdasarkan topik dosen
Route::get('/tugas-akhir/read', function () {
    return view('mahasiswa.TugasAkhir.form2');
});

// Menampilkan form batalkan tugas akhir
Route::get('/tugas-akhir/batal', function () {
    return view('mahasiswa.TugasAkhir.form3');
});

//daftar sidang
Route::get('/mahasiswa/sidang/tentukan-jadwal', function () {
    return view('mahasiswa/Sidang/form');
});

