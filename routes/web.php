<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\PendaftaranSidangController;

Route::get('/', function () {
    return view('home/homepage');
});

Route::prefix('mahasiswa')->group(function () {

    // Dashboard Mahasiswa
    Route::get('/', function () {
        return view('mahasiswa.views.dashboard');
    });

    Route::prefix('tugas-akhir')->group(function () {
        // Tampilkan form ajukan
        Route::get('/', function () {
            return view('mahasiswa.TugasAkhir.dashboard.dashboard');
        })->name('tugas-akhir.dashboard');

        // Menampilkan form progress TA
        Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugas-akhir.progress');

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
        Route::get('dashboard', function () {
            return view('mahasiswa.Bimbingan.dashboard.dashboard');
        })->name('dashboard.bimbingan');

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
        // Route::get('/daftar-sidang', function () {
        //     return view('mahasiswa.sidang.views.form');
        // });
        Route::get('dashboard', function () {
            return view('mahasiswa.Sidang.dashboard.dashboard');
        })->name('dashboard.sidang');

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
