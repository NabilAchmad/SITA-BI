<?php

use Illuminate\Support\Facades\Route;

// Ketua Program Studi
Route::get('/ketua-prodi', function () {
    return view('kaprodi/dashboard');
})->name('kaprodi.page');

// Jadwal sidang
Route::get('/kaprodi/sidang/lihat-jadwal', function () {
    return view('kaprodi/jadwal/readJadwal');
})->name('kaprodijadwal.page');

// Tugas Akhir
Route::get('/kaprodi/judulTA/AccJudulTA', function() {
    return view('kaprodi/judulTA/AccJudulTA');
})->name('accjudul.page');