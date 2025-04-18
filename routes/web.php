<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/admin', function () {
    return view('adminPage');
})->name('admin.page');

// Buat Pengumuman
Route::get('/pengumuman/create', function() {
    return view('createPengumuman');
})->name('pengumuman.create');

// Lihat Pengumuman
Route::get('/pengumuman/read', function() {
    return view('readPengumuman');
})->name('pengumuman.read');

// buat berita acara
Route::get('/berita-acara/create', function() {
    return view('buatBeritaAcara');
})->name('berita-acara.create');

// lihat berita acara
Route::get('/berita-acara/read', function() {
    return view('lihatBeritaAcara');
})->name('berita-acara.read');

// tentukan jadwal sidang
Route::get('/sidang/tentukan-jadwal', function() {
    return view('tentukanJadwalSidang');
})->name('jadwal-sidang.create');