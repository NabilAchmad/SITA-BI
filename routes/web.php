<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Models\Pengumuman;

Route::prefix('admin')->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [PengumumanController::class, 'tampil'])->name('admin.dashboard');
    });

    // =========================
    // ROUTE PENGUMUMAN
    // =========================
    Route::prefix('pengumuman')->group(function () {
        // READ
        Route::get('/read', [PengumumanController::class, 'read'])->name('pengumuman.read');

        // CREATE
        Route::get('/create', [PengumumanController::class, 'create'])->name('pengumuman.form'); // Form tambah
        Route::post('/create', [PengumumanController::class, 'store'])->name('pengumuman.create'); // Simpan data baru
        
        
        // EDIT / UPDATE
        Route::get('/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit'); // Form edit
        Route::put('/{id}/update', [PengumumanController::class, 'update'])->name('pengumuman.update'); // Update data
        
        // DELETE (Soft Delete)
        Route::delete('/{id}/soft-delete', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy'); // Soft delete
        
        // DELETE ALL (Force delete)
        Route::delete('/force-delete-all', [PengumumanController::class, 'forceDeleteAll'])->name('pengumuman.force-delete-all');
        
        // TRASHED (Manajemen soft delete)
        Route::get('/trash', [PengumumanController::class, 'trashed'])->name('pengumuman.trashed'); // Tampilkan data terhapus
        Route::post('/{id}/restore', [PengumumanController::class, 'restore'])->name('pengumuman.restore'); // Restore data
        Route::delete('/{id}/force-delete', [PengumumanController::class, 'forceDelete'])->name('pengumuman.force-delete'); // Hapus permanen

    });

    // =========================
    // ROUTE Mahasiswa
    // =========================
    Route::prefix('mahasiswa')->group(function () {
        // Mahasiswa
        Route::view('/list-mahasiswa', 'admin/mahasiswa/views/list-mhs')->name('list-mahasiswa');

        Route::view('/pilih-pembimbing', 'admin/mahasiswa/views/pilihPembimbing')->name('pilih-pembimbing');
    });

    // =========================
    // ROUTE BERITA ACARA
    // =========================
    Route::prefix('berita-acara')->group(function () {
        // Berita Acara
        Route::view('/create', 'admin/berita-acara/views/createBeritaAcara')->name('berita-acara.create');
        Route::view('/edit', 'admin/berita-acara/views/edit-berita-acara')->name('berita-acara.edit');
        Route::view('/read', 'admin/berita-acara/views/readBeritaAcara')->name('berita-acara.read'); 
    });

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
