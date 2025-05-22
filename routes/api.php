<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\KajurController;

Route::middleware('api')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('dosen', DosenController::class);

    // Kajur API routes
    Route::resource('kajur', KajurController::class);

    // Kaprodi API routes
    Route::resource('kaprodi', KaprodiController::class);
});
