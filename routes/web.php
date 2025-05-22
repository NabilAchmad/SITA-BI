<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PenugasanPembimbingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalSidangController;

// Ketua Program Studi
use App\Http\Controllers\KajurController;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\PendaftaranSidangController;

Route::get('/', function () {
    return view('home.homepage');
});

Route::prefix('mahasiswa')->group(function () {

// Mahasiswa Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('ketua-prodi')->group(function () {
    Route::get('/', [KajurController::class, 'index'])->name('kaprodi.page');

    // Jadwal sidang
    Route::get('/sidang/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kaprodijadwal.page');

    // Tugas Akhir
    Route::get('/judulTA/AccJudulTA', [KajurController::class, 'showAccJudulTA'])->name('accjudul.page');

    // Routes for Kaprodi to approve or reject JudulTA
    Route::post('/judulTA/approve/{id}', [App\Http\Controllers\KaprodiController::class, 'approveJudul'])->name('kaprodi.judulTA.approve');
    Route::post('/judulTA/reject/{id}', [App\Http\Controllers\KaprodiController::class, 'rejectJudul'])->name('kaprodi.judulTA.reject');

    // Nilai sidang
    Route::get('/sidang/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kaprodi.nilai.page');

    Route::get('/sidang/create', [KajurController::class, 'createSidang'])->name('kaprodi.nilai.create');
    Route::post('/sidang/create', [KajurController::class, 'storeSidang'])->name('kaprodi.nilai.store');

    // Pengumuman
    Route::get('/pengumuman', [KajurController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
});

// Admin Dashboard and related routes

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

    // =========================
    // ROUTE SIDANG
    // =========================
    Route::prefix('sidang')->group(function () {
        Route::get('/list-mahasiswa', [MahasiswaController::class, 'mahasiswaBelumPunyaJadwal'])
            ->name('mahasiswa-sidang.read');

        // Route::view('/lihat-jadwal', 'admin/sidang/jadwal/views/readJadwalSidang')->name('jadwal-sidang.read');
        Route::get('/lihat-jadwal', [JadwalSidangController::class, 'index'])->name('jadwal-sidang.read');

        Route::get('/edit-jadwal/{id}', [JadwalSidangController::class, 'edit'])->name('jadwal-sidang.edit');
        Route::put('/update-jadwal/{id}', [JadwalSidangController::class, 'update'])->name('jadwal-sidang.update');
        Route::delete('/delete-jadwal/{id}', [JadwalSidangController::class, 'destroy'])->name('jadwal-sidang.destroy');
    });

    // Laporan dan Statistik
    Route::view('/laporan/lihat', 'admin/laporan/views/lihatLaporanStatistik')->name('laporan.statistik');

    // Logs
    Route::view('/logs/lihat', 'admin/log/views/lihatLogAktifitas')->name('log.aktifitas');

    // Profile
    Route::view('/profile', 'admin/user/views/profile')->name('user.profile');
});
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
        // Route::get('/daftar-sidang', function () {
        //     return view('mahasiswa.sidang.views.form');
        // });

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
