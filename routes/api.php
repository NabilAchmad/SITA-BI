<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

// Mahasiswa API routes
Route::get('/mahasiswa', [MahasiswaProfileController::class, 'apiIndex']);
Route::get('/mahasiswa/{id}', [MahasiswaProfileController::class, 'apiShow']);
Route::post('/mahasiswa', [MahasiswaProfileController::class, 'apiStore']);
Route::put('/mahasiswa/{id}', [MahasiswaProfileController::class, 'apiUpdate']);
Route::delete('/mahasiswa/{id}', [MahasiswaProfileController::class, 'apiDestroy']);

