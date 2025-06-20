<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TawaranTopikController;
use App\Http\Controllers\PenugasanPembimbingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalSidangAkhirController;
use App\Http\Controllers\JadwalSidangSemproController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\PenilaianSidangController;
use App\Http\Controllers\TugasAkhirController;

Route::prefix('dosen')->group(function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    });

     // bimbingan
    Route::prefix('bimbingan')->group(function () {
        // Daftar bimbingan
        Route::get('/', [BimbinganController::class, 'dashboard'])->name('bimbingan.index');

        Route::get('/belumMulai', [BimbinganController::class, 'ajukanJadwal'])->name('bimbingan.crud-bimbingan.ajukan.jadwal');

        Route::get('/sedangBerlangsung', [BimbinganController::class, 'lihatBimbingan'])->name('bimbingan.crud-bimbingan.lihat.bimbingan');

        // ROUTE UNTUK AJUKAN PERUBAHAN JADWAL (EDIT JADWAL)
        Route::get('/menungguReview', function () {
            return view('admin.bimbingan.crud-bimbingan.ajukan-perubahan');
        })->name('bimbingan.crud-bimbingan.ajukan.perubahan');

        // ROUTE UNTUK TOLAK BIMBINGAN (POST)
        Route::post('/tolak', [BimbinganController::class, 'tolak'])->name('bimbingan.tolak');
    });


    // =========================
    // ROUTE TawaranTopik TOPIK
    // =========================


    Route::prefix('TawaranTopik')->group(function () {
        // READ
        Route::get('/read', [TawaranTopikController::class, 'read'])->name('TawaranTopik.read');

        // CREATE
        Route::get('/create', [TawaranTopikController::class, 'create'])->name('TawaranTopik.form'); // Form tambah
        Route::post('/create', [TawaranTopikController::class, 'store'])->name('TawaranTopik.create'); // Simpan data baru

        // EDIT / UPDATE
        Route::get('/{id}/edit', [TawaranTopikController::class, 'edit'])->name('TawaranTopik.edit'); // Form edit
        Route::put('/{id}/update', [TawaranTopikController::class, 'update'])->name('TawaranTopik.update'); // Update data

        // DELETE (Soft Delete)
        Route::delete('/{id}/soft-delete', [TawaranTopikController::class, 'destroy'])->name('TawaranTopik.destroy'); // Soft delete

        // DELETE ALL (Force delete)
        Route::delete('/force-delete-all', [TawaranTopikController::class, 'forceDeleteAll'])->name('TawaranTopik.force-delete-all');

        // TRASHED (Manajemen soft delete)
        Route::get('/trash', [TawaranTopikController::class, 'trashed'])->name('TawaranTopik.trashed'); // Tampilkan data terhapus
        Route::post('/{id}/restore', [TawaranTopikController::class, 'restore'])->name('TawaranTopik.restore'); // Restore data
        Route::delete('/{id}/force-delete', [TawaranTopikController::class, 'forceDelete'])->name('TawaranTopik.force-delete'); // Hapus permanen
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

         Route::get('/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'SidangSempro'])
        ->name('sidang.kelola.sempro'); // ← perbaikan di sini

        Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])
        ->name('jadwal-sempro.simpanPenguji');

        Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])
        ->name('jadwal-sempro.store');

        Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])
        ->name('jadwal-sempro.show');

        Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])
        ->name('jadwal-sidang-sempro.mark-done');
});


        Route::prefix('akhir')->group(function () {

             // ✅ Halaman utama kelola jadwal sidang akhir (semua tab: menunggu, jadwal, lulus, tidak lulus)
        Route::get('/jadwal-sidang-akhir', [JadwalSidangAkhirController::class, 'sidangAkhir'])
            ->name('jadwal.sidang.akhir');

        // ✅ Alias tambahan (opsional) jika Blade masih memanggil route ini
        Route::get('/menunggu', [JadwalSidangAkhirController::class, 'sidangAkhir'])
            ->name('sidang.menunggu.penjadwalan.akhir');

        // ✅ Simpan data jadwal sidang
        Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])
            ->name('jadwal-sidang.store');

        // ✅ Lihat detail jadwal sidang akhir
        Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])
            ->name('jadwal-sidang.show');

        // ✅ Update & hapus jadwal sidang
        Route::put('/update-jadwal/{id}', [JadwalSidangAkhirController::class, 'update'])
            ->name('jadwal-sidang.update');
        Route::delete('/delete-jadwal/{id}', [JadwalSidangAkhirController::class, 'destroy'])
            ->name('jadwal-sidang.destroy');

        // ✅ Simpan penguji
        Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])
            ->name('jadwal-sidang.simpanPenguji');

        // ✅ Tandai status sidang
        Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
            ->name('jadwal-sidang.mark-done');

            // ============================
            // 5. Routes nilai sidang
            // ============================

            Route::prefix('penilaian')->group(function () {
                Route::get('/sidang', [PenilaianSidangController::class, 'index'])->name('penilaian.sidang.index');
                Route::get('/sidang/{id}/form', [PenilaianSidangController::class, 'form'])->name('penilaian.sidang.form');
                Route::post('/sidang/{id}/simpan', [PenilaianSidangController::class, 'simpan'])->name('penilaian.sidang.simpan');
            });

            // Tandai akhir sidang selesai 
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
                ->name('jadwal-sidang.mark-done');

            // Daftar mahasiswa yang sudah sidang akhir


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


    //tugas akhir

    Route::prefix('ta')->group(function () {
    // 1. Halaman dashboard TA
    Route::get('/dashboard', [TugasAkhirController::class, 'dashboard'])->name('ta.dashboard');

    // 2. Kirim komentar revisi (dari modal revisi)
    Route::post('/revisi', [TugasAkhirController::class, 'revisiStore'])->name('ta.revisi');

    // 3. Aksi ACC dan Tolak per revisi
    Route::post('/acc/{id}', [TugasAkhirController::class, 'acc'])->name('ta.acc');
    Route::post('/tolak/{id}', [TugasAkhirController::class, 'tolak'])->name('ta.tolak');
});


    // Profile
    Route::view('/profile', 'admin/user/views/profile')->name('user.profile');

    Route::view('/arsip-ta', 'admin/arsip/dashboard/arsip')->name('arsip-ta.index');
});
