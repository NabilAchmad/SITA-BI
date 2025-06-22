<?php

use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\TugasAkhirController;
use App\Http\Controllers\Mahasiswa\PendaftaranSidangController;
use App\Http\Controllers\Mahasiswa\TopikController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;
use App\Models\TawaranTopik;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KajurController;
use App\Http\Controllers\KaprodiController;
// use App\Http\Controllers\TugasAkhirController;
// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PenugasanPembimbingController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JadwalSidangAkhirController;
use App\Http\Controllers\Admin\JadwalSidangSemproController;
// use App\Http\Controllers\PengumumanController;
// use App\Http\Controllers\PenugasanPembimbingController;
// use App\Http\Controllers\MahasiswaController;
// use App\Http\Controllers\DosenController;
// use App\Http\Controllers\LaporanController;
// use App\Http\Controllers\LogController;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\JadwalSidangAkhirController;
// use App\Http\Controllers\JadwalSidangSemproController;
// use App\Http\Controllers\KajurController;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\KaprodiController;
// use App\Http\Controllers\TugasAkhirController;
// use App\Http\Controllers\PendaftaranSidangController;
use App\Models\JudulTA;
use App\Models\TugasAkhir;

// Homepage
Route::get('/', function () {
    return view('home.homepage');
});

Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin')->group(function () {

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
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    });

    // =========================
    // ROUTE PENGUMUMAN
    // =========================
    Route::prefix('pengumuman')->group(function () {
        // READ
        Route::get('/read', [PengumumanController::class, 'read'])->name('pengumuman.read');

    });
});



// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// OTP verification routes
Route::get('/otp-verification', [AuthController::class, 'showOtpForm'])->name('auth.otp.form');
Route::post('/otp-verification', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');

Route::get('/auth/read', function () {
    return view('auth.read');
})->name('auth.read');

// Kajur Routes
Route::prefix('ketua-jurusan')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('kajur.login');
    Route::post('/login', [AuthController::class, 'login'])->name('kajur.login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('kajur.logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/', [KajurController::class, 'index'])->name('kajur.dashboard');

        // Tugas Akhir
        Route::get('/judulTA/JudulTA', [KajurController::class, 'showJudulTA'])->name('kajur.judul.page');
        Route::get('/judulTA/acc', [KajurController::class, 'showAcc'])->name('kajur.judul.acc');
        Route::get('/judulTA/tolak', [KajurController::class, 'showTolak'])->name('kajur.judul.tolak');

        // Pengumuman
        Route::get('/pengumuman', [KajurController::class, 'showPengumuman'])->name('kajurpengumuman.page');

        Route::get('/ajukan-jadwal', [BimbinganController::class, 'ajukanJadwal'])->name('bimbingan.ajukanJadwal');
        Route::post('/store', [BimbinganController::class, 'store'])->name('simpan.jadwal');

        Route::get('/jadwal-bimbingan', [BimbinganController::class, 'jadwalBimbingan'])->name('jadwal.bimbingan');

        Route::put('/bimbingan/jadwal/{id}', [BimbinganController::class, 'ubahJadwal'])->name('bimbingan.updateJadwal');

        Route::get('/revisi', function () {
            return view('mahasiswa.Bimbingan.views.revisiTA');
        });

        // Nilai Sidang routes for Kajur
        Route::get('/nilai/edit/{id}', [KajurController::class, 'editNilai'])->name('kajur.nilai.edit');
        Route::post('/nilai/update/{id}', [KajurController::class, 'updateNilai'])->name('kajur.nilai.update');

        // Bimbingan routes for Kajur
        Route::get('/bimbingan', [KajurController::class, 'indexBimbingan'])->name('kajur.bimbingan.index');
        Route::get('/bimbingan/create', [KajurController::class, 'createBimbingan'])->name('kajur.bimbingan.create');
        Route::post('/bimbingan/store', [KajurController::class, 'storeBimbingan'])->name('kajur.bimbingan.store');
    });

        // Sidang Routes
        Route::get('/sidang/dashboard', [KajurController::class, 'showSidangDashboard'])->name('sidangDashboard.kajur');
        Route::get('/sidang/mahasiswaSidang', [KajurController::class, 'showMahasiswaSidang'])->name('kajur.sidang');
        Route::get('/sidang/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kajur.jadwal');
        Route::get('/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kajur.nilai.page');

        // Add dashboard cards routes for Kajur sidang dashboard
        Route::get('/sidang/dashboard-cards', [KajurController::class, 'showSidangDashboard'])->name('sidangDashboard.kajur.cards');
    });

    // Sidang Routes for Kajur
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
        Route::get('/sempro/penjadwalan-sidang-sempro', [JadwalSidangSemproController::class, 'menungguSidangSempro'])->name('kajur.sidang.menunggu.sempro');
        Route::get('/sempro/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'listJadwalSempro'])->name('kajur.sidang.jadwal.sempro');
        Route::get('/sempro/pasca-sidang-sempro', [JadwalSidangSemproController::class, 'pascaSidangSempro'])->name('kajur.sidang.pasca.sempro');

        Route::get('/akhir/penjadwalan-sidang-akhir', [JadwalSidangAkhirController::class, 'menungguSidangAkhir'])->name('kajur.sidang.menunggu.akhir');
        Route::get('/akhir/jadwal-sidang-akhir', [JadwalSidangAkhirController::class, 'listJadwalAkhir'])->name('kajur.sidang.jadwal.akhir');
        Route::get('/akhir/pasca-sidang-akhir', [JadwalSidangAkhirController::class, 'pascaSidangAkhir'])->name('kajur.sidang.pasca.akhir');

        // Additional routes for dashboard and other sidang views
        Route::get('/dashboard', [KajurController::class, 'showSidangDashboard'])->name('sidangDashboard.kajur');
        Route::get('/mahasiswaSidang', [KajurController::class, 'showMahasiswaSidang'])->name('kajur.sidang');
        Route::get('/lihat-jadwal', [KajurController::class, 'showJadwal'])->name('kajur.jadwal');
        Route::get('/lihat-nilai', [KajurController::class, 'showNilaiSidang'])->name('kajur.nilai.page');

        // Add missing routes for menunggu sempro and menunggu akhir views
        Route::get('/dashboard-sidang', [KajurController::class, 'showSidangDashboard'])->name('dashboard-sidang.kajur');
        Route::get('/sempro/menunggu', [KajurController::class, 'showMahasiswaSidangSempro'])->name('kajur.sidang.menunggu.sempro');
        Route::get('/akhir/menunggu', [KajurController::class, 'showMahasiswaMenungguAkhir'])->name('kajur.sidang.menunggu.akhir');

        // Daftar mahasiswa sudah punya pembimbing
        Route::get('/list-mahasiswa', [PenugasanPembimbingController::class, 'indexPembimbing'])->name('list-mahasiswa');

        // Update pembimbing (edit via modal)
        Route::put('/update-pembimbing/{tugasAkhirId}', [PenugasanPembimbingController::class, 'update'])->name('pembimbing.update');
        // Add dashboard-sidang route for kajur
        Route::get('/dashboard-sidang', [KajurController::class, 'showSidangDashboard'])->name('dashboard-sidang.kajur');
    });


    // =========================
    // ROUTE KELOLA AKUN
    // =========================
    Route::prefix('kelola-akun')->group(function () {

        // Dosen
        Route::prefix('dosen')->group(function () {
            Route::get('/', [DosenController::class, 'index'])->name('akun-dosen.kelola');
        });

        // Ketua Prodi (Kaprodi) Routes
        Route::prefix('ketua-prodi')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('kaprodi.login');
            Route::post('/login', [AuthController::class, 'login'])->name('kaprodi.login.post');
            Route::put('/logout', [AuthController::class, 'logout'])->name('kaprodi.logout');

            Route::middleware(['auth'])->group(function () {
                Route::get('/', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');

                // Jadwal sidang
                Route::get('/sidang/dashboard', [KaprodiController::class, 'showSidangDashboard'])->name('sidangDashboard.page');
                Route::get('/sidang/mahasiswaSidang', [KaprodiController::class, 'showMahasiswaSidang'])->name('kaprodi.mahasiswa.sidang');
                Route::get('/sidang/lihat-jadwal', [KaprodiController::class, 'showJadwal'])->name('kaprodi.jadwal');

                //Kaprodi Lihat Nilai
                Route::get('/nilai', [KaprodiController::class, 'sidangAkhir'])->name('kaprodi.nilai.page');

                // Tugas Akhir
                Route::get('/judulTA/JudulTA', [KaprodiController::class, 'showJudulTA'])->name('kaprodi.judul.page');
                Route::post('/judulTA/approve/{id}', [TugasAkhirController::class, 'approve'])->name('kaprodi.judul.approve');
                Route::post('/judulTA/reject/{id}', [TugasAkhirController::class, 'reject'])->name('kaprodi.judul.reject');
                Route::get('/judulTA/acc', [KaprodiController::class, 'showAcc'])->name('kaprodi.judul.acc');
                Route::get('/judulTA/tolak', [KaprodiController::class, 'showTolak'])->name('kaprodi.judul.tolak');
                Route::get('/judulTA/similar/{id}', [KaprodiController::class, 'getSimilarJudul'])->name('kaprodi.judul.similar');

                Route::get('/pengumuman', [KaprodiController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
                Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('kaprodi.dashboard-sidang');

                Route::prefix('sidang')->group(function () {
                    Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('kaprodi.dashboard-sidang');

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
                        Route::prefix('sempro')->group(function () {
                            Route::get('/penjadwalan-sidang-sempro', [JadwalSidangSemproController::class, 'menungguSidangSempro'])->name('kaprodisidang.menunggu.penjadwalan.sempro');
                            Route::get('/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'listJadwalSempro'])->name('kaprodijadwal.sidang.sempro');
                            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])->name('kaprodijadwal-sempro.simpanPenguji');
                            Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])->name('kaprodijadwal-sempro.store');
                            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])->name('kaprodijadwal-sempro.show');
                            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])->name('jadwal-sidang-sempro.mark-done');
                            Route::get('pasca-sidang-sempro', [JadwalSidangSemproController::class, 'pascaSidangSempro'])->name('kaprodipasca.sidang.sempro');
                        });

                        Route::get('/nilai/sidang', [KaprodiController::class, 'SidangAkhir'])->name('kaprodi.akhir.page');
                        Route::get('/sidang/create', [KaprodiController::class, 'createSidang'])->name('kaprodi.nilai.create');
                        Route::post('/sidang/create', [KaprodiController::class, 'storeSidang'])->name('kaprodi.nilai.store');

                        // Route for Kaprodi to view Nilai


                        // New routes for detailed views of dashboard cards
                        Route::get('/sidang/menunggu-sempro', [KaprodiController::class, 'showMahasiswaSidangSempro'])->name('kaprodi.sidang.menunggu.sempro');
                        Route::get('/sidang/menunggu-akhir', [KaprodiController::class, 'showMahasiswaMenungguAkhir'])->name('kaprodi.sidang.menunggu.akhir');
                        Route::get('/sidang/jadwal-sempro', [KaprodiController::class, 'showSidangSemproTerjadwal'])->name('kaprodi.sidang.jadwal.sempro');
                        Route::get('/sidang/jadwal-akhir', [KaprodiController::class, 'showSidangAkhirTerjadwal'])->name('kaprodi.sidang.jadwal.akhir');
                        Route::get('/sidang/pasca-sempro', [KaprodiController::class, 'showPascaSidangSempro'])->name('kaprodi.sidang.pasca.sempro');
                        Route::get('/sidang/pasca-akhir', [KaprodiController::class, 'showPascaSidangAkhir'])->name('kaprodi.sidang.pasca.akhir');
                    });
                });
            });
        });

        // Admin Routes
        Route::prefix('admin')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
            Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
            Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

            Route::middleware(['auth'])->group(function () {
                Route::get('/', function () {
                    return redirect()->route('admin.dashboard');
                });

                Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

                // Add dashboard-sidang route for admin

                // Other admin routes...
            });
            return view('loginRegister');
        });

        // Ketua Prodi (Kaprodi) Routes
        Route::prefix('ketua-prodi')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('kaprodi.login');
            Route::post('/login', [AuthController::class, 'login'])->name('kaprodi.login.post');
            Route::put('/logout', [AuthController::class, 'logout'])->name('kaprodi.logout');

            Route::middleware(['auth'])->group(function () {
                Route::get('/', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');

                // Jadwal sidang
                Route::get('/sidang/dashboard', [KaprodiController::class, 'showSidangDashboard'])->name('sidangDashboard.page');
                Route::get('/sidang/mahasiswaSidang', [KaprodiController::class, 'showMahasiswaSidang'])->name('kaprodi.mahasiswa.sidang');
                Route::get('/sidang/lihat-jadwal', [KaprodiController::class, 'showJadwal'])->name('kaprodi.jadwal');

                //Kaprodi Lihat Nilai
                Route::get('/nilai', [KaprodiController::class, 'sidangAkhir'])->name('kaprodi.nilai.page');

                // Tugas Akhir
                Route::get('/judulTA/JudulTA', [KaprodiController::class, 'showJudulTA'])->name('kaprodi.judul.page');
                Route::post('/judulTA/approve/{id}', [TugasAkhirController::class, 'approve'])->name('kaprodi.judul.approve');
                Route::post('/judulTA/reject/{id}', [TugasAkhirController::class, 'reject'])->name('kaprodi.judul.reject');
                Route::get('/judulTA/acc', [KaprodiController::class, 'showAcc'])->name('kaprodi.judul.acc');
                Route::get('/judulTA/tolak', [KaprodiController::class, 'showTolak'])->name('kaprodi.judul.tolak');
                Route::get('/judulTA/similar/{id}', [KaprodiController::class, 'getSimilarJudul'])->name('kaprodi.judul.similar');

                Route::get('/pengumuman', [KaprodiController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
                Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('kaprodi.dashboard-sidang');

                Route::prefix('sidang')->group(function () {
                    Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('kaprodi.dashboard-sidang');

                    Route::prefix('sempro')->group(function () {
                        Route::get('/penjadwalan-sidang-sempro', [JadwalSidangSemproController::class, 'menungguSidangSempro'])->name('kaprodisidang.menunggu.penjadwalan.sempro');
                        Route::get('/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'listJadwalSempro'])->name('kaprodijadwal.sidang.sempro');
                        Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])->name('kaprodijadwal-sempro.simpanPenguji');
                        Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])->name('kaprodijadwal-sempro.store');
                        Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])->name('kaprodijadwal-sempro.show');
                        Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])->name('jadwal-sidang-sempro.mark-done');
                        Route::get('pasca-sidang-sempro', [JadwalSidangSemproController::class, 'pascaSidangSempro'])->name('kaprodipasca.sidang.sempro');
                    });

                    Route::get('/nilai/sidang', [KaprodiController::class, 'SidangAkhir'])->name('kaprodi.akhir.page');
                    Route::get('/sidang/create', [KaprodiController::class, 'createSidang'])->name('kaprodi.nilai.create');
                    Route::post('/sidang/create', [KaprodiController::class, 'storeSidang'])->name('kaprodi.nilai.store');

                    // Route for Kaprodi to view Nilai


                    // New routes for detailed views of dashboard cards
                    Route::get('/sidang/menunggu-sempro', [KaprodiController::class, 'showMahasiswaSidangSempro'])->name('kaprodi.sidang.menunggu.sempro');
                    Route::get('/sidang/menunggu-akhir', [KaprodiController::class, 'showMahasiswaMenungguAkhir'])->name('kaprodi.sidang.menunggu.akhir');
                    Route::get('/sidang/jadwal-sempro', [KaprodiController::class, 'showSidangSemproTerjadwal'])->name('kaprodi.sidang.jadwal.sempro');
                    Route::get('/sidang/jadwal-akhir', [KaprodiController::class, 'showSidangAkhirTerjadwal'])->name('kaprodi.sidang.jadwal.akhir');
                    Route::get('/sidang/pasca-sempro', [KaprodiController::class, 'showPascaSidangSempro'])->name('kaprodi.sidang.pasca.sempro');
                    Route::get('/sidang/pasca-akhir', [KaprodiController::class, 'showPascaSidangAkhir'])->name('kaprodi.sidang.pasca.akhir');
                });
            });
        });

        // Admin Routes
        Route::prefix('admin')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
            Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
            Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

            Route::middleware(['auth'])->group(function () {
                Route::get('/', function () {
                    return redirect()->route('admin.dashboard');
                });

                Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

                // Add dashboard-sidang route for admin

                // Other admin routes...
            });
            return view('loginRegister');
        });
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [MahasiswaProfileController::class, 'profile'])->name('user.profile');
        Route::put('/update', [MahasiswaProfileController::class, 'update'])->name('user.profile.update');
        Route::put('/logout', [MahasiswaProfileController::class, ''])->name('logout');
    });
});
