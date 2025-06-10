<?php

use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\PendaftaranSidangController;
use App\Http\Controllers\TopikController;
use App\Models\TawaranTopik;

Route::prefix('/mahasiswa')->group(function () {

    // Dashboard Mahasiswa
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.mahasiswa');

    Route::prefix('/tugas-akhir')->group(function () {
        Route::get('/', [TugasAkhirController::class, 'dashboard'])->name('tugas-akhir.dashboard');

        // Menampilkan form ajukan Tugas Akhir
        Route::get('/ajukan-ta-mandiri', [TugasAkhirController::class, 'ajukanForm'])->name('tugas-akhir.ajukan');

        // Menampilkan form progress TA
        Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugas-akhir.progress');

        // Menangani revisi TA
        Route::get('/revisi', [TugasAkhirController::class, ''])->name('tugas-akhir.revisi');

        // Tangani form POST ajukan TA
        Route::post('/ajukan', [TugasAkhirController::class, 'store'])->name('tugasAkhir.store');

        Route::delete('tugasAkhir/{id}', [TugasAkhirController::class, 'destroy'])->name('tugasAkhir.destroy');
        Route::post('tugasAkhir/{id}/cancel', [TugasAkhirController::class, 'cancel'])->name('tugasAkhir.cancelTA');
        Route::get('tugasAkhir/dibatalkan', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.dibatalkan');

        // Menampilkan form ajukan berdasarkan topik dosen
        Route::get('/list-topik-dosen', [TopikController::class, 'index'])->name('mahasiswa.topik.index');
        Route::get('/ambil-topik', [TopikController::class, ''])->name('mahasiswa.topik.ambil');

        Route::get('/cancel', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.cancelled');
    });


    Route::prefix('bimbingan')->group(function () {
        // Tambahkan route untuk Bimbingan di sini jika diperlukan
        Route::get('/', [BimbinganController::class, 'dashboard'])->name('dashboard.bimbingan');

        Route::get('/ajukan-jadwal', [BimbinganController::class, 'ajukanJadwal'])->name('bimbingan.ajukanJadwal');
        Route::post('/store', [BimbinganController::class, 'store'])->name('simpan.jadwal');

        Route::get('/jadwal-bimbingan', [BimbinganController::class, 'jadwalBimbingan'])->name('jadwal.bimbingan');

        Route::get('/ubah-jadwal', [BimbinganController::class, 'ubahJadwal'])->name('ubah.jadwal');

        Route::get('/revisi', function () {
            return view('mahasiswa.Bimbingan.views.revisiTA');
        });
    });

    Route::prefix('sidang')->group(function () {
        Route::get('dashboard', function () {
            return view('mahasiswa.Sidang.dashboard.dashboard');
        })->name('dashboard.sidang');

        //sempro
        Route::get('/daftar-sempro', function () {
            return view('mahasiswa.Sidang.views.sempro');
        })->name('daftar-sempro');


        Route::get('/lihat-nilai', function () {
            return view('mahasiswa.sidang.views.nilaiSidang');
        });

        Route::get('/lihat-jadwal', function () {
            return view('mahasiswa.sidang.views.jadwal');
        });

        Route::get('/daftar-sidang', [PendaftaranSidangController::class, 'form'])->name('pendaftaran_sidang.form');
        Route::post('/daftar-sidang', [PendaftaranSidangController::class, 'store'])->name('pendaftaran_sidang.store');
    });
});
