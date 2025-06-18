<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PenugasanPembimbingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalSidangAkhirController;
use App\Http\Controllers\JadwalSidangSemproController;

Route::prefix('admin')->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // =========================
    // ROUTE PENGUMUMAN
    // =========================
    Route::prefix('pengumuman')->group(function () {
        // READ
        Route::get('/read', [PengumumanController::class, 'read'])->name('pengumuman.read');

        // CREATE
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
        Route::get('/belum-pembimbing', [PenugasanPembimbingController::class, 'indexWithOutPembimbing'])->name('penugasan-bimbingan.index');

        // Form pilih pembimbing untuk mahasiswa tertentu
        Route::get('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'create'])->name('penugasan-bimbingan.create');
        Route::post('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'store'])->name('penugasan-bimbingan.store');

        // Daftar mahasiswa sudah punya pembimbing
        Route::get('/list-mahasiswa', [PenugasanPembimbingController::class, 'indexPembimbing'])->name('list-mahasiswa');

        // Update pembimbing (edit via modal)
        Route::put('/update-pembimbing/{tugasAkhirId}', [PenugasanPembimbingController::class, 'update'])->name('pembimbing.update');
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
            Route::get('/search', [MahasiswaController::class, 'search'])->name('akun-mahasiswa.search');
        });
    });

    // =========================
    // ROUTE SIDANG
    // =========================
    Route::prefix('sidang')->group(function () {

        Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dashboard-sidang');

        Route::prefix('sempro')->group(function () {
            // Daftar mahasiswa yang belum punya jadwal sidang sempro
            Route::get('/penjadwalan-sidang-sempro', [JadwalSidangSemproController::class, 'SidangSempro'])->name('sidang.kelola.sempro');

            // Simpan penguji
            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])->name('jadwal-sempro.simpanPenguji');

            // Simpan data jadwal sidang
            Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])->name('jadwal-sempro.store');

            // Lihat Detail Jadwal Sidang akhir
            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])->name('jadwal-sempro.show');

            // Tandai akhir sidang selesai 
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])
                ->name('jadwal-sidang-sempro.mark-done');
        });

        Route::prefix('akhir')->group(function () {
            // Daftar mahasiswa yang belum punya jadwal sidang akhir
            Route::get('/penjadwalan-sidang-akhir', [JadwalSidangAkhirController::class, 'SidangAkhir'])->name('sidang.kelola.akhir');

            // Simpan data jadwal sidang
            Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])->name('jadwal-sidang.store');

            // Lihat Detail Jadwal Sidang akhir
            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])->name('jadwal-sidang.show');

            // Edit dan Hapus Jadwal Sidang
            Route::put('/update-jadwal/{id}', [JadwalSidangAkhirController::class, 'update'])->name('jadwal-sidang.update');
            Route::delete('/delete-jadwal/{id}', [JadwalSidangAkhirController::class, 'destroy'])->name('jadwal-sidang.destroy');

            // Tandai akhir sidang selesai 
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
                ->name('jadwal-sidang.mark-done');

            // Halaman Pasca Sidang
            Route::get('/pasca-sidang-akhir', [JadwalSidangAkhirController::class, 'pascaSidangAkhir'])
                ->name('pasca.sidang.akhir');

            // POST: Simpan dosen penguji
            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');
        });
    });

    // Admin: Laporan dan Statistik
    Route::prefix('/laporan')->name('laporan.')->group(function () {
        // Lihat laporan dan statistik
        Route::get('/lihat', [LaporanController::class, 'show'])
            ->name('statistik');
    });

    // Admin: Logs
    Route::prefix('/logs')->name('log.')->group(function () {
        // Lihat log aktivitas sistem
        Route::get('/lihat', [LogController::class, 'index'])
            ->name('aktifitas');
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [AdminController::class, 'profile'])->name('user.profile');
        Route::put('/update', [AdminController::class, 'update'])->name('user.profile.update');
        Route::put('/logout', [AdminController::class, ''])->name('logout');
    });
});
