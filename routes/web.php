<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasAkhirController;

Route::get('/', function () {
    return view('home/homepage');
});

Route::prefix('mahasiswa')->group(function () {

    // Dashboard Mahasiswa
    Route::get('/', function () {
        return view('mahasiswa.views.dashboard');
    });

    Route::prefix('TugasAkhir')->group(function () {
        // Tampilkan form ajukan
        Route::get('/ajukan', function () {
            return view('mahasiswa.TugasAkhir.views.ajukanTA');
        });

        // Menampilkan form progress TA
        Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugasAkhir.progress');

        // Tangani form POST ajukan TA
        Route::post('/ajukan', [TugasAkhirController::class, 'store'])->name('tugasAkhir.store');

        Route::resource('tugasAkhir', TugasAkhirController::class);

        Route::delete('tugasAkhir/{id}', [TugasAkhirController::class, 'destroy'])->name('tugasAkhir.destroy');
        Route::post('tugasAkhir/{id}/cancel', [TugasAkhirController::class, 'cancel'])->name('tugasAkhir.cancelTA');
        Route::get('tugasAkhir/dibatalkan', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.dibatalkan');

        // Menampilkan form ajukan berdasarkan topik dosen
        Route::get('/read', function () {
            return view('mahasiswa.TugasAkhir.views.listTopik');
        });

        Route::get('/cancel', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.cancelled');
    });


    Route::prefix('bimbingan')->group(function () {
        // Tambahkan route untuk Bimbingan di sini jika diperlukan

        Route::get('/ajukan-jadwal', function () {
            return view('mahasiswa.Bimbingan.views.ajukanBimbingan');
        });

        Route::get('/lihat-jadwal', function () {
            return view('mahasiswa.Bimbingan.views.lihatJadwal');
        });

        Route::get('/revisi', function () {
            return view('mahasiswa.Bimbingan.views.revisiTA');
        });

        Route::get('/perubahan-jadwal', function () {
            return view('
            mahasiswa.Bimbingan.views.perubahanJadwal');
        });
    });

    Route::prefix('sidang')->group(function () {
        // Tambahkan route untuk Sidang di sini jika diperlukan
        Route::get('/daftar-sidang', function () {
            return view('mahasiswa.sidang.views.form');
        });

        Route::get('/lihat-nilai', function () {
            return view('mahasiswa.sidang.views.nilaiSidang');
        });

        Route::get('/lihat-jadwal', function () {
            return view('mahasiswa.sidang.views.jadwal');
        });
    });
});
