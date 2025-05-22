<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;

Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

// Mahasiswa API routes
Route::get('/mahasiswa', [MahasiswaController::class, 'apiIndex']);
Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'apiShow']);
Route::post('/mahasiswa', [MahasiswaController::class, 'apiStore']);
Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'apiUpdate']);
Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'apiDestroy']);
