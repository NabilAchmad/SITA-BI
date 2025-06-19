<?php

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
