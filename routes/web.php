<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PenugasanPembimbingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalSidangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AdminController;

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
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
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
        Route::get('/list-mahasiswa', [MahasiswaController::class, 'mahasiswaBelumPunyaJadwal'])
            ->name('mahasiswa-sidang.read');

        // Form untuk memilih penguji 
        Route::get('/pilih-penguji/{sidang_id}', [JadwalSidangController::class, 'showFormPenguji'])->name('jadwal-sidang.pilihPenguji');

        // POST: Simpan dosen penguji
        Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');

        // Form jadwal sidang
        Route::get('/jadwal-sidang/create', [JadwalSidangController::class, 'create'])->name('jadwal-sidang.create');

        // Simpan data jadwal sidang
        Route::post('/jadwal-sidang', [JadwalSidangController::class, 'store'])->name('jadwal-sidang.store');

        // Lihat Jadwal Sidang
        Route::get('/lihat-jadwal', [JadwalSidangController::class, 'index'])->name('jadwal-sidang.read');

        // Lihat Detail Jadwal Sidang
        Route::get('/detail-sidang/{sidang_id}', [JadwalSidangController::class, 'show'])->name('jadwal-sidang.show');

        // Tandai sidang selesai
        Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangController::class, 'tandaiSidang'])
            ->name('jadwal-sidang.mark-done');

        // Halaman Pasca Sidang
        Route::get('/pasca-sidang', [JadwalSidangController::class, 'pascaSidang'])
            ->name('jadwal-sidang.pasca-sidang');

        // Edit dan Hapus Jadwal Sidang
        Route::put('/update-jadwal/{id}', [JadwalSidangController::class, 'update'])->name('jadwal-sidang.update');
        Route::delete('/delete-jadwal/{id}', [JadwalSidangController::class, 'destroy'])->name('jadwal-sidang.destroy');
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
        Route::get('/daftar-sidang', function () {
            return view('mahasiswa.sidang.views.form');
        });

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
