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
use App\Models\JudulTA;
use App\Models\TugasAkhir;

// Homepage
// Route::get('/', function () {
//     return view('home.homepage');
// });
Route::get('/', function () {
    return view('home.homepage');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
// Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('email.resend');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/verify', [AuthController::class, 'verifyEmail'])->name('email.verify.post');
// Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('email.resend');
// Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('email.resend');
// Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('email.resend');
// Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('email.resend');
// Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
// Route::get('/email/verify', [AuthController::class, 'showEmailVerificationForm'])->name('email.verify.form');
// Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('email.resend');


// Kajur Routes
Route::prefix('kajur')->group(function () {
    // General User Login Routes inside kajur prefix (optional, depends on your auth strategy)
    // Route::get('/login', [AuthController::class, 'showLogin'])->name('kajur.login');
    // Route::post('/login', [AuthController::class, 'login'])->name('kajur.login.post');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [KajurController::class, 'index'])->name('kajur.dashboard');

        // Tugas Akhir
        Route::get('/judulTA/JudulTA', [KajurController::class, 'showAccJudulTA'])->name('kajur.judul.page');

        // Pengumuman
        Route::get('/pengumuman', [KajurController::class, 'showPengumuman'])->name('kajurpengumuman.page');

        // Sidang Routes
        Route::get('/sidang/dashboard', [KajurController::class, 'showSidangDashboard'])->name('sidangDashboard.kajur');
        Route::get('/sidang/mahasiswaSidang', [KajurController::class, 'showMahasiswaSidang'])->name('kajur.sidang');
        Route::get('/sidang/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kajur.jadwal');
        Route::get('/sidang/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kajur.nilai.page');
        // Route::prefix('sidang')->group(function () {
        //     Route::get('/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kajur.jadwal');
        //     Route::get('/mahasiswaSidang', [KajurController::class, 'showMahasiswaSidang'])->name('kajur.sidang');
        //     Route::get('/dashboard', [KajurController::class, 'showSidangDashboard'])->name('sidangDashboard.kajur');
        //     Route::get('/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kajur.nilai.page');
        // });
    });
});

// Ketua Prodi (Kaprodi) Routes
Route::prefix('ketua-prodi')->group(function () {
    // Kaprodi Login (if needed)
    // Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    // Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware(['auth'])->group(function () {
        Route::get('/', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');

        // Jadwal sidang
        Route::get('/sidang/dashboard', [KaprodiController::class, 'showSidangDashboard'])->name('sidangDashboard.page');
        Route::get('/sidang/mahasiswaSidang', [KaprodiController::class, 'showMahasiswaSidang'])->name('kaprodi.mahasiswa.sidang');
        Route::get('/sidang/lihat-jadwal', [KaprodiController::class, 'showJadwal'])->name('kaprodi.jadwal');


        // Tugas Akhir
        Route::get('/judulTA/JudulTA', [KaprodiController::class, 'showAccJudulTA'])->name('kaprodi.judul.page');
        // Route untuk ACC dan Tolak Judul
        Route::post('/ketua-prodi/judulTA/approve/{id}', [TugasAkhirController::class, 'approve'])->name('kaprodi.judul.approve');
        Route::post('/ketua-prodi/judulTA/reject/{id}', [TugasAkhirController::class, 'reject'])->name('kaprodi.judul.reject');
        // Route untuk melihat daftar judul yang sudah di-ACC
        Route::get('/judulTA/acc', [KaprodiController::class, 'showAccJudulTA'])->name('kaprodi.judul.acc');
        // Route untuk melihat daftar judul yang sudah ditolak
        Route::get('/judulTA/tolak', [KaprodiController::class, 'showTolakJudulTA'])->name('kaprodi.judul.tolak');


        // Nilai sidang
        Route::get('/sidang/akhir', [KaprodiController::class, 'SidangAkhir'])->name('kaprodi.akhir.page');
        Route::get('/sidang/create', [KaprodiController::class, 'createSidang'])->name('kaprodi.nilai.create');
        Route::post('/sidang/create', [KaprodiController::class, 'storeSidang'])->name('kaprodi.nilai.store');

        // Pengumuman
        Route::get('/pengumuman', [KaprodiController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Authentication
    // Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    // Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    // Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth'])->group(function () {
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
    });

    // =========================
    // ROUTE SIDANG
    // =========================
    Route::prefix('sidang')->group(function () {

        Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dashboard-sidang');

        Route::prefix('sempro')->group(function () {
            // Daftar mahasiswa yang belum punya jadwal sidang sempro
            Route::get('/penjadwalan-sidang-sempro', [JadwalSidangSemproController::class, 'MenungguSidangSempro'])->name('sidang.menunggu.penjadwalan.sempro');

            // Daftar mahasiswa yang sudah punya jadwal sidang
            Route::get('/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'listJadwalSempro'])->name('jadwal.sidang.sempro');

            // Simpan penguji
            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])->name('jadwal-sempro.simpanPenguji');

            // Simpan data jadwal sidang
            Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])->name('jadwal-sempro.store');

            // Lihat Detail Jadwal Sidang akhir
            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])->name('jadwal-sempro.show');

            // Tandai akhir sidang selesai 
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])
                ->name('jadwal-sidang-sempro.mark-done');

            // Daftar mahasiswa yang sudah sidang
            Route::get('pasca-sidang-sempro', [JadwalSidangSemproController::class, 'pascaSidangSempro'])->name('pasca.sidang.sempro');

            Route::prefix('/logs')->name('log.')->group(function () {
                Route::get('/lihat', [LogController::class, 'index'])->name('aktifitas');
            });

            // Profile
            Route::view('/profile', 'admin/user/views/profile')->name('user.profile');
        });
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

            Route::prefix('akhir')->group(function () {
                // Daftar mahasiswa yang belum punya jadwal sidang akhir
                Route::get('/penjadwalan-sidang-akhir', [JadwalSidangAkhirController::class, 'MenungguSidangAkhir'])->name('sidang.menunggu.penjadwalan.akhir');

                // Daftar mahasiswa yang sudah punya jadwal sidang akhir
                Route::get('/jadwal-sidang-akhir', [JadwalSidangAkhirController::class, 'listJadwalAkhir'])->name('jadwal.sidang.akhir');

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
    });
});
