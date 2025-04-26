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

// Pengumuman
Route::get('/kaprodi/pengumuman', function () {
    return view('kaprodi/Pengumuman/pengumuman');
})->name('kaprodipengumuman.page');

// Admin Dashboard
Route::get('/admin', function(){
    return view('admin/dashboard');
})->name('admin.page');

// Pengumuman CRUD for Admin
Route::prefix('admin/pengumuman')->group(function () {
    // Read all pengumuman
    Route::get('/', function () {
        return view('admin/pengumuman/readPengumuman');
    })->name('admin.pengumuman.index');

    // Create pengumuman
    Route::get('/pengumuman/create', function () {
        return view('admin/pengumuman/createPengumuman');
    })->name('admin.pengumuman.create');
    Route::post('/create', function () {
        // Logic to store pengumuman
    })->name('admin.pengumuman.store');

    // Update pengumuman
    Route::get('/pengumuman/edit', function () {
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

// jadwal sidang
Route::get('/admin/sidang/lihat-jadwal', function () {
    return view('admin/sidang/jadwal/readJadwalSidang');
})->name('admin.jadwal.page');

// nilai sidang

Route::get('/kaprodi/sidang/lihat-nilai', function(){
    return view('kaprodi/sidang/readSidang');
})->name('kaprodi.nilai.page');

Route::get('/kaprodi/sidang/create', function(){
    return view('kaprodi/sidang/createSidang');
})->name('kaprodi.nilai.create');