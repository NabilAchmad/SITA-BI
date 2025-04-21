<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home/homepage');
});

Route::get('/admin', function () {
    return view('admin/dashboard');
})->name('admin.page');

// // Buat Pengumuman
Route::get('/pengumuman/create', function() {
    return view('admin/pengumuman/createPengumuman');
})->name('pengumuman.create');

// Lihat Pengumuman
Route::get('/pengumuman/read', function() {
    return view('admin/pengumuman/readPengumuman');
})->name('pengumuman.read');

// edit Pengumuman
Route::get('/pengumuman/edit', function() {
    return view('admin/pengumuman/editPengumuman');
})->name('pengumuman.edit');

// buat berita acara
Route::get('/berita-acara/create', function() {
    return view('admin/sidang/berita-acara/createBeritaAcara');
})->name('berita-acara.create');

// lihat berita acara
Route::get('/berita-acara/read', function() {
    return view('admin/sidang/berita-acara/readBeritaAcara');
})->name('berita-acara.read');

// tentukan jadwal sidang
Route::get('/sidang/tentukan-jadwal', function() {
    return view('admin/sidang/jadwal/createJadwalSidang');
})->name('jadwal-sidang.create');

// lihat jadwal sidang
Route::get('/sidang/lihat-jadwal', function() {
    return view('admin/sidang/jadwal/readJadwalSidang');
})->name('jadwal-sidang.read');

// kelola akun dosen
Route::get('/kelola-akun/dosen', function() {
    return view('admin/dosen/kelolaAkunDosen');
})->name('akun-dosen.kelola');

// Tambah akun dosen
Route::get('/kelola-akun/dosen/tambah', function() {
    return view('createDosen');
})->name('akun-dosen.tambah');

// kelola akun mahasiswa
Route::get('/kelola-akun/mahasiswa', function() {
    return view('admin/mahasiswa/kelolaMahasiswa');
})->name('akun-mahasiswa.kelola');

// lihat laporan dan statistik
Route::get('/laporan/lihat', function() {
    return view('admin/laporan/lihatLaporanStatistik');
})->name('laporan.statistik');

// lihat log dan aktifitas
Route::get('/logs/lihat', function() {
    return view('admin/log/lihatLogAktifitas');
})->name('log.aktifitas');

// profile 
Route::get('/admin/profile', function() {
    return view('admin/user/profile');
})->name('profile.detail');

// KETUA JURUSAN
Route::get('/ketua-jurusan', function () {
    return view('kajur/dashboard');
})->name('ketua-jurusan.page');

// lihat jadwal sidang
Route::get('kajur/sidang/lihat-jadwal', function() {
    return view('kajur/sidang/lihat-jadwal');
})->name('jadwal-sidang.kajur.readJadwal');

// lihat mahasiswa sidang
Route::get('kajur/sidang/lihat-mahasiswa', function() {
    return view('kajur/sidang/lihat-mahasiswa');
})->name('mahasiswa-sidang.kajur.readMahasiswa');

// lihat pengumuman
Route::get('kajur/pengumuman/lihat-pengumuman', function() {
    return view('kajur/pengumuman/lihat-pengumuman');
})->name('mahasiswa-sidang.kajur.readPengumuman');

// Ketua Program Studi
Route::get('/ketua-prodi', function () {
    return view('kaprodi/dashboard');
})->name('kaprodi.page');