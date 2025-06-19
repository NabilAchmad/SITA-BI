<?php

use App\Http\Controllers\Api\DosenApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaApiController;

// Existing user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mahasiswa API routes
Route::prefix('mahasiswa')->group(function () {
    Route::get('/', [MahasiswaApiController::class, 'index']);
    Route::get('/{id}', [MahasiswaApiController::class, 'show']);
    Route::post('/', [MahasiswaApiController::class, 'store']);
    Route::put('/{id}', [MahasiswaApiController::class, 'update']);
    Route::delete('/{id}', [MahasiswaApiController::class, 'destroy']);
});

// Dosen API routes
Route::prefix('dosen')->group(function () {
    // Route::get('/', [DosenApiController::class, 'index']);
    // Route::get('/{id}', [DosenApiController::class, 'show']);
    // Route::post('/', [DosenApiController::class, 'store']);
    // Route::put('/{id}', [DosenApiController::class, 'update']);
    // Route::delete('/{id}', [DosenApiController::class, 'destroy']);
    // Route::apiResource('/', DosenApiController::class);
});
Route::get('/dosen', [DosenApiController::class, 'index']);
Route::get('/{id}', [DosenApiController::class, 'show']);
Route::post('/dosen', [DosenApiController::class, 'store']);
Route::put('/editdosen/{id}', [DosenApiController::class, 'update']);
Route::delete('/deletedosen/{id}', [DosenApiController::class, 'destroy']);
