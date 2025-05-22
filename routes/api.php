<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;

Route::middleware('api')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'apiIndex']);
    Route::get('/dosen', [DosenController::class, 'apiIndex']);

    // Kajur API routes
    Route::get('/kajur/jadwal', [App\Http\Controllers\KajurController::class, 'apiJadwal']);
    Route::get('/kajur/judul-ta', [App\Http\Controllers\KajurController::class, 'apiJudulTA']);
    Route::get('/kajur/nilai-sidang', [App\Http\Controllers\KajurController::class, 'apiNilaiSidang']);
    Route::get('/kajur/pengumuman', [App\Http\Controllers\KajurController::class, 'apiPengumuman']);

    // Kaprodi API routes
    Route::get('/kaprodi/jadwal', [App\Http\Controllers\KaprodiController::class, 'apiJadwal']);
    Route::get('/kaprodi/judul-ta', [App\Http\Controllers\KaprodiController::class, 'apiJudulTA']);
    Route::get('/kaprodi/nilai-sidang', [App\Http\Controllers\KaprodiController::class, 'apiNilaiSidang']);
    Route::get('/kaprodi/pengumuman', [App\Http\Controllers\KaprodiController::class, 'apiPengumuman']);
    Route::post('/kaprodi/judul-ta/approve/{id}', [App\Http\Controllers\KaprodiController::class, 'approveJudul']);
    Route::post('/kaprodi/judul-ta/reject/{id}', [App\Http\Controllers\KaprodiController::class, 'rejectJudul']);
});
