<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::view('/', 'admin/views/dashboard')->name('admin.dashboard');

    // Pengumuman
    Route::view('/pengumuman/create', 'admin/pengumuman/views/createPengumuman')->name('pengumuman.create');
    Route::view('/pengumuman/read', 'admin/pengumuman/views/readPengumuman')->name('pengumuman.read');
    Route::view('/pengumuman/edit', 'admin/pengumuman/views/editPengumuman')->name('pengumuman.edit');

    // Tugas Akhir
    Route::view('/tugas-akhir/pilih-pembimbing', 'admin/tugas-akhir/views/pilihPembimbing')->name('tugas-akhir.pilih-pembimbing');
    Route::view('/tugas-akhir/list-mahasiswa', 'admin/tugas-akhir/views/list-mhs')->name('tugas-akhir.list-mahasiswa');

    // Berita Acara
    Route::view('/berita-acara/create', 'admin/sidang/berita-acara/views/createBeritaAcara')->name('berita-acara.create');
    Route::view('/berita-acara/edit', 'admin/sidang/berita-acara/views/edit-berita-acara')->name('berita-acara.edit');
    Route::view('/berita-acara/read', 'admin/sidang/berita-acara/views/readBeritaAcara')->name('berita-acara.read');

    // Sidang
    Route::view('/sidang/tentukan-jadwal', 'admin/sidang/jadwal/views/createJadwalSidang')->name('jadwal-sidang.create');
    Route::view('/sidang/edit-jadwal', 'admin/sidang/jadwal/views/editJadwalSidang')->name('jadwal-sidang.edit');
    Route::view('/sidang/lihat-jadwal', 'admin/sidang/jadwal/views/readJadwalSidang')->name('jadwal-sidang.read');
    Route::view('/sidang/list-mahasiswa', 'admin/sidang/jadwal/views/read-mhs-sidang')->name('mahasiswa-sidang.read');

    // Kelola Akun
    Route::view('/kelola-akun/dosen', 'admin/dosen/views/kelolaAkunDosen')->name('akun-dosen.kelola');
    Route::view('/kelola-akun/dosen/tambah', 'admin/dosen/views/createDosen')->name('akun-dosen.tambah');
    Route::view('/kelola-akun/mahasiswa', 'admin/mahasiswa/views/kelolaMahasiswa')->name('akun-mahasiswa.kelola');
    Route::view('/kelola-akun/mahasiswa/edit', 'admin/mahasiswa/views/editMahasiswa')->name('akun-mahasiswa.edit');

    // Laporan dan Statistik
    Route::view('/laporan/lihat', 'admin/laporan/views/lihatLaporanStatistik')->name('laporan.statistik');

    // Logs
    Route::view('/logs/lihat', 'admin/log/views/lihatLogAktifitas')->name('log.aktifitas');

    // Profile
    Route::view('/profile', 'admin/user/views/profile')->name('user.profile');
});
