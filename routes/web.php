<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PenugasanPembimbingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;

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
        // Daftar mahasiswa belum punya pembimbing
        Route::get('/belum-pembimbing', [PenugasanPembimbingController::class, 'index'])->name('penugasan-bimbingan.index');

        // Form pilih pembimbing untuk mahasiswa tertentu
        Route::get('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'create'])->name('penugasan-bimbingan.create');
        Route::post('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'store'])->name('penugasan-bimbingan.store');

        // Daftar mahasiswa sudah punya pembimbing
        Route::get('/list-mahasiswa', [MahasiswaController::class, 'index'])->name('list-mahasiswa');
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

    // =========================
    // ROUTE KELOLA AKUN
    // =========================
    Route::prefix('kelola-akun')->group(function () {

        // Dosen
        Route::prefix('dosen')->group(function () {
            Route::get('/', [DosenController::class, 'index'])->name('akun-dosen.kelola');

            Route::get('/edit/{id}', [DosenController::class, 'edit'])->name('akun-dosen.edit');
            Route::put('/update/{id}', [DosenController::class, 'update'])->name('akun-dosen.update');
            Route::delete('/hapus/{id}', [DosenController::class, 'destroy'])->name('akun-dosen.destroy');


            Route::get('/tambah-akun-dosen', [DosenController::class, 'create'])->name('akun-dosen.create');
            Route::post('/tambah-akun-dosen', [DosenController::class, 'store'])->name('akun-dosen.store');
        });

        Route::prefix('mahasiswa')->group(function () {
            // Mahasiswa
            Route::get('/', [MahasiswaController::class, 'listMahasiswa'])->name('akun-mahasiswa.kelola');
            Route::get('/edit/{id}', [MahasiswaController::class, 'edit'])->name('akun-mahasiswa.edit');
            Route::put('/update/{id}', [MahasiswaController::class, 'update'])->name('akun-mahasiswa.update');
        });
    });

    // Sidang
    Route::view('/sidang/tentukan-jadwal', 'admin/sidang/jadwal/views/createJadwalSidang')->name('jadwal-sidang.create');
    Route::view('/sidang/edit-jadwal', 'admin/sidang/jadwal/views/editJadwalSidang')->name('jadwal-sidang.edit');
    Route::view('/sidang/lihat-jadwal', 'admin/sidang/jadwal/views/readJadwalSidang')->name('jadwal-sidang.read');
    Route::view('/sidang/list-mahasiswa', 'admin/sidang/jadwal/views/read-mhs-sidang')->name('mahasiswa-sidang.read');

    // Laporan dan Statistik
    Route::view('/laporan/lihat', 'admin/laporan/views/lihatLaporanStatistik')->name('laporan.statistik');

    // Logs
    Route::view('/logs/lihat', 'admin/log/views/lihatLogAktifitas')->name('log.aktifitas');

    // Profile
    Route::view('/profile', 'admin/user/views/profile')->name('user.profile');
});
