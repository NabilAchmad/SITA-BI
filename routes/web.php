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

// edit Pengumuman
Route::get('/pengumuman/edit', function() {
    return view('editPengumuman');
})->name('pengumuman.edit');

// buat berita acara
Route::get('/berita-acara/create', function() {
    return view('createBeritaAcara');
})->name('berita-acara.create');

// lihat berita acara
Route::get('/berita-acara/read', function() {
    return view('readBeritaAcara');
})->name('berita-acara.read');

// tentukan jadwal sidang
Route::get('/sidang/tentukan-jadwal', function() {
    return view('createJadwalSidang');
})->name('jadwal-sidang.create');

// lihat jadwal sidang
Route::get('/sidang/lihat-jadwal', function() {
    return view('readJadwalSidang');
})->name('jadwal-sidang.read');

// kelola akun dosen
Route::get('/kelola-akun/dosen', function() {
    return view('kelolaAkunDosen');
})->name('akun-dosen.kelola');

// Tambah akun dosen
Route::get('/kelola-akun/dosen/tambah', function() {
    return view('createDosen');
})->name('akun-dosen.tambah');

// kelola akun mahasiswa
Route::get('/kelola-akun/mahasiswa', function() {
    return view('kelolaMahasiswa');
})->name('akun-mahasiswa.kelola');

// lihat laporan dan statistik
Route::get('/laporan/lihat', function() {
    return view('lihatLaporanStatistik');
})->name('laporan.statistik');

// lihat log dan aktifitas
Route::get('/logs/lihat', function() {
    return view('lihatLogAktifitas');
})->name('log.aktifitas');

// profile 
Route::get('/admin/profile', function() {
    return view('profile');
})->name('profile.detail');