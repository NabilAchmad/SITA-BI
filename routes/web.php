<?php

use Illuminate\Support\Facades\Route;

// Ketua Program Studi
use App\Http\Controllers\KajurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;

Route::get('/', function () {
    return view('homepage');
});

// Mahasiswa Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




Route::prefix('ketua-prodi')->group(function () {
    Route::get('/', [KajurController::class, 'index'])->name('kaprodi.page');

    // Jadwal sidang
    Route::get('/sidang/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kaprodijadwal.page');

    // Tugas Akhir
    Route::get('/judulTA/AccJudulTA', [KajurController::class, 'showAccJudulTA'])->name('accjudul.page');

    // Nilai sidang
    Route::get('/sidang/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kaprodi.nilai.page');

    Route::get('/sidang/create', [KajurController::class, 'createSidang'])->name('kaprodi.nilai.create');
    Route::post('/sidang/create', [KajurController::class, 'storeSidang'])->name('kaprodi.nilai.store');

    // Pengumuman
    Route::get('/pengumuman', [KajurController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
});

// Admin Dashboard and related routes
Route::prefix('admin')->group(function () {
    Route::get('/', function(){
        return view('admin/dashboard');
    })->name('admin.page');

    // Pengumuman CRUD for Admin
    Route::prefix('pengumuman')->group(function () {
        // Read all pengumuman
        Route::get('/', function () {
            return view('admin/pengumuman/readPengumuman');
        })->name('admin.pengumuman.index');

        // Create pengumuman
        Route::get('/create', function () {
            return view('admin/pengumuman/createPengumuman');
        })->name('admin.pengumuman.create');
        Route::post('/create', function () {
            // Logic to store pengumuman
        })->name('admin.pengumuman.store');

        // Update pengumuman
        Route::get('/edit', function () {
            return view('admin/pengumuman/editPengumuman');
        })->name('admin.pengumuman.edit');
        Route::put('/edit/{id}', function ($id) {
            // Logic to update pengumuman
        })->name('admin.pengumuman.update');

        // Delete pengumuman
        Route::delete('/delete/{id}', function ($id) {
            // Logic to delete pengumuman
        })->name('admin.pengumuman.delete');
    });

    // Jadwal sidang
    Route::get('/sidang/lihat-jadwal', function () {
        return view('admin/sidang/jadwal/readJadwalSidang');
    })->name('admin.jadwal.page');
});
