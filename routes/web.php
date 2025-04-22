<?php

use Illuminate\Support\Facades\Route;

// Ketua Program Studi
Route::get('/ketua-prodi', function () {
    return view('kaprodi/dashboard');
})->name('kaprodi.page');

// Dosen
Route::get('/kaprodi/sidang/lihat-jadwal', function () {
    return view('kaprodi/jadwal/readjadwal');
})->name('kaprodi/jadwal.page');