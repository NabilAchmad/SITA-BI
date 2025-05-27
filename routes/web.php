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
use App\Http\Controllers\KajurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\TugasAkhirController;
use App\Http\Controllers\PendaftaranSidangController;

// Homepage
Route::get('/', function () {
    return view('home.homepage');
});

// Kajur Routes
Route::prefix('kajur')->group(function () {
    Route::get('/dashboard', [KajurController::class, 'index'])->name('kajur.dashboard');

    // Tugas Akhir
    Route::get('/judulTA/AccJudulTA', [KajurController::class, 'showAccJudulTA'])->name('kajur.accjudul.page');
    Route::post('/judulTA/approve/{id}', [KajurController::class, 'approveJudul'])->name('kajur.judulTA.approve');
    Route::post('/judulTA/reject/{id}', [KajurController::class, 'rejectJudul'])->name('kajur.judulTA.reject');
    Route::get('/judulTA/similarity/{id}', [KajurController::class, 'showSimilarity'])->name('kajur.judulTA.similarity');

    // Pengumuman
    Route::get('/pengumuman', [KajurController::class, 'showPengumuman'])->name('kajurpengumuman.page');

    // Sidang Routes
    Route::prefix('sidang')->group(function () {
        Route::get('/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kajur.jadwal');
        Route::get('/mahasiswaSidang', [KajurController::class, 'showMahasiswaSidang'])->name('kajur.sidang');
        Route::get('/dashboard', [KajurController::class, 'showSidangDashboard'])->name('sidangDashboard.kajur');
        Route::get('/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kajur.nilai.page');
        Route::get('/create', [KajurController::class, 'createSidang'])->name('kajur.nilai.create');
    });

    // General User Login Routes inside kajur prefix (optional, depends on your auth strategy)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('kajur.login');
    Route::post('/login', [AuthController::class, 'login'])->name('kajur.login.post');
});

// Ketua Prodi (Kaprodi) Routes
Route::prefix('ketua-prodi')->group(function () {
    Route::get('/', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');

    // Jadwal sidang
    Route::get('/sidang/dashboard', [KaprodiController::class, 'showSidangDashboard'])->name('sidangDashboard.page');
    Route::get('/sidang/mahasiswaSidang', [KaprodiController::class, 'showMahasiswaSidang'])->name('kaprodi.mahasiswa.sidang');
    Route::get('/sidang/lihat-jadwal', [KaprodiController::class, 'showJadwal'])->name('kaprodi.jadwal');

    // Tugas Akhir
    Route::get('/judulTA/AccJudulTA', [KaprodiController::class, 'showAccJudulTA'])->name('accjudul.page');
    Route::post('/judulTA/approve/{id}', [KaprodiController::class, 'approveJudul'])->name('kaprodi.judulTA.approve');
    Route::post('/judulTA/reject/{id}', [KaprodiController::class, 'rejectJudul'])->name('kaprodi.judulTA.reject');

    // Nilai sidang
    Route::get('/sidang/lihat-nilai', [KaprodiController::class, 'showNilaiSidang'])->name('kaprodi.nilai.page');
    Route::get('/sidang/create', [KaprodiController::class, 'createSidang'])->name('kaprodi.nilai.create');
    Route::post('/sidang/create', [KaprodiController::class, 'storeSidang'])->name('kaprodi.nilai.store');

    // Pengumuman
    Route::get('/pengumuman', [KaprodiController::class, 'showPengumuman'])->name('kaprodipengumuman.page');

    // Kaprodi Login (if needed)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('kaprodi.login');
    Route::post('/login', [AuthController::class, 'login'])->name('kaprodi.login.post');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Authentication
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Redirect base /admin to dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Pengumuman Routes
    Route::prefix('pengumuman')->group(function () {
        Route::get('/read', [PengumumanController::class, 'read'])->name('pengumuman.read');
        Route::get('/create', [PengumumanController::class, 'create'])->name('pengumuman.form');
        Route::post('/create', [PengumumanController::class, 'store'])->name('pengumuman.create');
        Route::get('/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
        Route::put('/{id}/update', [PengumumanController::class, 'update'])->name('pengumuman.update');
        Route::delete('/{id}/soft-delete', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
        Route::delete('/force-delete-all', [PengumumanController::class, 'forceDeleteAll'])->name('pengumuman.force-delete-all');
        Route::get('/trash', [PengumumanController::class, 'trashed'])->name('pengumuman.trashed');
        Route::post('/{id}/restore', [PengumumanController::class, 'restore'])->name('pengumuman.restore');
        Route::delete('/{id}/force-delete', [PengumumanController::class, 'forceDelete'])->name('pengumuman.force-delete');
    });

    // Mahasiswa Management Routes
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/belum-pembimbing', [PenugasanPembimbingController::class, 'indexWithOutPembimbing'])->name('penugasan-bimbingan.index');
        Route::get('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'create'])->name('penugasan-bimbingan.create');
        Route::post('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'store'])->name('penugasan-bimbingan.store');
        Route::get('/list-mahasiswa', [PenugasanPembimbingController::class, 'indexPembimbing'])->name('list-mahasiswa');
    });

    // Account Management Routes
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

        // Mahasiswa
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/', [MahasiswaController::class, 'listmahasiswa'])->name('akun-mahasiswa.kelola');
            Route::get('/edit/{id}', [MahasiswaController::class, 'edit'])->name('akun-mahasiswa.edit');
            Route::put('/update/{id}', [MahasiswaController::class, 'update'])->name('akun-mahasiswa.update');
            Route::delete('/hapus/{id}', [MahasiswaController::class, 'destroy'])->name('akun-mahasiswa.destroy');
            Route::get('/tambah-akun-mahasiswa', [MahasiswaController::class, 'create'])->name('akun-mahasiswa.create');
            Route::post('/tambah-akun-mahasiswa', [MahasiswaController::class, 'store'])->name('akun-mahasiswa.store');
        });
    });

    // Reports and Logs
    Route::prefix('/laporan')->name('laporan.')->group(function () {
        Route::get('/lihat', [LaporanController::class, 'show'])->name('statistik');
    });

    Route::prefix('/logs')->name('log.')->group(function () {
        Route::get('/lihat', [LogController::class, 'index'])->name('aktifitas');
    });

    // Profile
    Route::view('/profile', 'admin/user/views/profile')->name('user.profile');
});

// Dosen Routes
Route::prefix('dosen')->group(function () {
    // Authentication
    Route::get('/login', [AuthController::class, 'showLogin'])->name('dosen.login');
    Route::post('/login', [AuthController::class, 'login'])->name('dosen.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('dosen.logout');

    // Dashboard
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
        // Add other dosen routes here as needed
    });
});

// Mahasiswa Routes
Route::prefix('mahasiswa')->group(function () {
    // Authentication
    Route::get('/login', [AuthController::class, 'showLogin'])->name('mahasiswa.login');
    Route::post('/login', [AuthController::class, 'login'])->name('mahasiswa.login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('mahasiswa.logout');

    // Dashboard
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');

        // Additional mahasiswa routes like sidang, bimbingan can be nested here
        Route::prefix('sidang')->group(function () {
            Route::get('/daftar-sidang', [PendaftaranSidangController::class, 'form'])->name('pendaftaran_sidang.form');
            Route::post('/daftar-sidang', [PendaftaranSidangController::class, 'store'])->name('pendaftaran_sidang.store');
            // Add other mahasiswa sidang routes here
        });

        Route::prefix('bimbingan')->group(function () {
            // Add mahasiswa bimbingan related routes here
        });
    });
});

// SIDANG Routes (public or shared, not specific to a user prefix)
Route::prefix('sidang')->group(function () {
    Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dashboard-sidang');

    Route::prefix('sempro')->group(function () {
        Route::get('penjadwalan', [JadwalSidangSemproController::class, 'menungguSidangSempro'])->name('sidang.menunggu.penjadwalan.sempro');
        Route::get('jadwal', [JadwalSidangSemproController::class, 'listJadwalSempro'])->name('jadwal.sidang.sempro');
        Route::get('pasca', [JadwalSidangSemproController::class, 'pascaSidangSempro'])->name('pasca.sidang.sempro');
    });

    Route::prefix('akhir')->group(function () {
        Route::get('penjadwalan', [JadwalSidangAkhirController::class, 'MenungguSidangAkhir'])->name('sidang.menunggu.penjadwalan.akhir');
        Route::get('jadwal', [JadwalSidangAkhirController::class, 'listJadwal'])->name('jadwal.sidang.akhir');
        Route::get('/pasca-sidang-akhir', [JadwalSidangAkhirController::class, 'pascaSidangAkhir'])->name('pasca.sidang.akhir');
    });
});