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
use App\Models\Mahasiswa;

Route::get('/', function () {
    return view('home.homepage');
});

// Kajur routes
Route::prefix('kajur')->group(function () {
    // Authentication routes for Kajur
    // Route::get('/login', [AuthController::class, 'showLogin'])->name('kajur.login');
    // Route::post('/login', [AuthController::class, 'login'])->name('kajur.login.post');
    // Route::post('/logout', [AuthController::class, 'logout'])->name('kajur.logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [KajurController::class, 'index'])->name('kajur.dashboard');
        // Add other Kajur routes here as needed
    });
});

// Kaprodi routes
Route::prefix('kaprodi')->group(function () {
    // Authentication routes for Kaprodi
    // Route::get('/login', [AuthController::class, 'showLogin'])->name('kaprodi.login');
    // Route::post('/login', [AuthController::class, 'login'])->name('kaprodi.login.post');
    // Route::post('/logout', [AuthController::class, 'logout'])->name('kaprodi.logout');

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');

        // Jadwal sidang
        Route::get('/sidang/lihat-jadwal', [KaprodiController::class, 'showJadwal'])->name('kaprodijadwal.page');

        // Tugas Akhir
        Route::get('/judulTA/AccJudulTA', [KaprodiController::class, 'showAccJudulTA'])->name('accjudul.page');

        // Routes for Kaprodi to approve or reject JudulTA
        Route::post('/judulTA/approve/{id}', [KaprodiController::class, 'approveJudul'])->name('kaprodi.judulTA.approve');
        Route::post('/judulTA/reject/{id}', [KaprodiController::class, 'rejectJudul'])->name('kaprodi.judulTA.reject');

        // Nilai sidang
        Route::get('/sidang/lihat-nilai', [KaprodiController::class, 'showNilaiSidang'])->name('kaprodi.nilai.page');

        Route::get('/sidang/create', [KaprodiController::class, 'createSidang'])->name('kaprodi.nilai.create');
        Route::post('/sidang/create', [KaprodiController::class, 'storeSidang'])->name('kaprodi.nilai.store');

        // Pengumuman
        Route::get('/pengumuman', [KaprodiController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
    });
});

// General user login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');






// // Authentication routes for Dosen
// Route::get('/dosen/login', [AuthController::class, 'showLogin'])->name('dosen.login');
// Route::post('/dosen/login', [AuthController::class, 'login'])->name('dosen.login.post');
// Route::post('/dosen/logout', [AuthController::class, 'logout'])->name('dosen.logout');

// Authentication routes for Mahasiswa
// Route::get('/mahasiswa/login', [AuthController::class, 'showLogin'])->name('mahasiswa.login');
// Route::post('/mahasiswa/login', [AuthController::class, 'login'])->name('mahasiswa.login.post');


// Mahasiswa Dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');

    // Dashboard view route
    // Changed route from '/' to '/dashboard-view' to avoid conflict with public homepage route
    Route::get('/dashboard-view', function () {
        return view('mahasiswa.views.dashboard');
    });
});

// Move ketua-prodi routes outside auth middleware to allow access without login
Route::prefix('ketua-prodi')->group(function () {
    // Add base /mahasiswa/ketua-prodi route to redirect to dashboard
    Route::get('/', [KaprodiController::class, 'index'])->name('kaprodi.dashboard');

    // Jadwal sidang
    Route::get('/sidang/lihat-jadwal', [KaprodiController::class, 'showJadwal'])->name('kaprodijadwal.page');

    // Tugas Akhir
    Route::get('/judulTA/AccJudulTA', [KaprodiController::class, 'showAccJudulTA'])->name('accjudul.page');

    // Routes for Kaprodi to approve or reject JudulTA
    Route::post('/judulTA/approve/{id}', [KaprodiController::class, 'approveJudul'])->name('kaprodi.judulTA.approve');
    Route::post('/judulTA/reject/{id}', [KaprodiController::class, 'rejectJudul'])->name('kaprodi.judulTA.reject');

    // Nilai sidang
    Route::get('/sidang/lihat-nilai', [KaprodiController::class, 'showNilaiSidang'])->name('kaprodi.nilai.page');

    Route::get('/sidang/create', [KaprodiController::class, 'createSidang'])->name('kaprodi.nilai.create');
    Route::post('/sidang/create', [KaprodiController::class, 'storeSidang'])->name('kaprodi.nilai.store');

    // Pengumuman
    Route::get('/pengumuman', [KaprodiController::class, 'showPengumuman'])->name('kaprodipengumuman.page');
});

Route::prefix('TugasAkhir')->group(function () {
    // Tampilkan form ajukan
    Route::get('/ajukan', function () {
        return view('mahasiswa.TugasAkhir.views.ajukanTA');
    });
    Route::prefix('tugas-akhir')->group(function () {
        Route::get('/', function () {
            return view('mahasiswa.TugasAkhir.dashboard.dashboard');
        })->name('tugas-akhir.dashboard');

        // Menampilkan form ajukan Tugas Akhir
        Route::get('/ajukan', function () {
            return view('mahasiswa.TugasAkhir.views.ajukanTA');
        })->name('tugas-akhir.ajukan');

        // Menampilkan form progress TA
        Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugasAkhir.progress');
        // Menampilkan form progress TA
        Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugas-akhir.progress');

        //ta mandiri
        Route::get('/ajukan-ta-mandiri', function () {
            return view('mahasiswa.TugasAkhir.views.ajukanTA');
        })->name('ajukan-ta');

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
        // Menampilkan form ajukan berdasarkan topik dosen
        Route::get('/list-topik-dosen', function () {
            return view('mahasiswa.TugasAkhir.views.listTopik');
        })->name('list-topik');

        Route::get('/cancel', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.cancelled');
    });

    Route::prefix('bimbingan')->group(function () {
        // Tambahkan route untuk Bimbingan di sini jika diperlukan
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
                return view('mahasiswa.Bimbingan.views.perubahanJadwal');
            });
        });

        Route::prefix('sidang')->group(function () {
            // Tambahkan route untuk Sidang di sini jika diperlukan
            Route::get('/daftar-sidang', [MahasiswaController::class, 'daftarSidang'])->name('mahasiswa.sidang.daftar');
            Route::get('/lihat-nilai', function () {
                return view('mahasiswa.sidang.views.nilaiSidang');
            });
            Route::prefix('sidang')->group(function () {
                // Tambahkan route untuk Sidang di sini jika diperlukan
                // Route::get('/daftar-sidang', function () {
                //     return view('mahasiswa.sidang.views.form');
                // });
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
        Route::get('/daftar-sidang', [PendaftaranSidangController::class, 'form'])->name('pendaftaran_sidang.form');
        Route::post('/daftar-sidang', [PendaftaranSidangController::class, 'store'])->name('pendaftaran_sidang.store');
    });
    // });


    Route::prefix('admin')->group(function () {

        // Authentication routes for Admin
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Add base /admin route to redirect to /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

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
                // Add base /admin/kelola-akun/dosen route to show dosen index
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

        // =========================
        // ROUTE SIDANG
        // =========================
        Route::prefix('sidang')->group(function () {

            Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dashboard-sidang');

            Route::prefix('sempro')->group(function () {
                // Daftar mahasiswa yang belum punya jadwal sidang sempro
                Route::get('penjadwalan', [JadwalSidangAkhirController::class, ''])->name('sidang.menunggu.penjadwalan.sempro');
                // Daftar mahasiswa yang sudah punya jadwal sidang
                Route::get('jadwal', [JadwalSidangAkhirController::class, ''])->name('jadwal.sidang.sempro');
                // Daftar mahasiswa yang sudah sidang
                Route::get('pasca', [JadwalSidangAkhirController::class, ''])->name('pasca.sidang.sempro');
            });

            Route::prefix('akhir')->group(function () {
                // Daftar mahasiswa yang belum punya jadwal sidang akhir
                Route::get('penjadwalan', [JadwalSidangAkhirController::class, 'MenungguSidangAkhir'])->name('sidang.menunggu.penjadwalan.akhir');
                // Daftar mahasiswa yang sudah punya jadwal sidang akhir
                Route::get('jadwal', [JadwalSidangAkhirController::class, 'listJadwal'])->name('jadwal.sidang.akhir');
                // Daftar mahasiswa yang sudah sidang akhir
                // Halaman Pasca Sidang
                Route::get('/pasca-sidang-akhir', [JadwalSidangAkhirController::class, 'pascaSidangAkhir'])
                    ->name('pasca.sidang.akhir');
                Route::get('/pilih-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'modalDosen'])->name('jadwal-sidang.modal.dosen');
                // Form jadwal sidang akhir
                Route::get('/jadwal-sidang/create', [JadwalSidangAkhirController::class, 'modalForm'])->name('jadwal-sidang.modal.form');

                // POST: Simpan dosen penguji
                Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');

                // Simpan data jadwal sidang
                Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])->name('jadwal-sidang.store');
                // Lihat Detail Jadwal Sidang akhir
                Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])->name('jadwal-sidang.show');
                // Edit dan Hapus Jadwal Sidang
                Route::put('/update-jadwal/{id}', [JadwalSidangAkhirController::class, 'update'])->name('jadwal-sidang.update');
                Route::delete('/delete-jadwal/{id}', [JadwalSidangAkhirController::class, 'destroy'])->name('jadwal-sidang.destroy');
            });




            Route::get('/list-mahasiswa', [MahasiswaController::class, 'mahasiswaBelumPunyaJadwal'])
                ->name('mahasiswa-sidang.read');

            // Form untuk memilih penguji
            Route::get('/pilih-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'showFormPenguji'])->name('jadwal-sidang.pilihPenguji');

            // POST: Simpan dosen penguji
            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');

            // Form jadwal sidang
            Route::get('/jadwal-sidang/create', [JadwalSidangAkhirController::class, 'create'])->name('jadwal-sidang.create');

            // Simpan data jadwal sidang
            Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])->name('jadwal-sidang.store');

            // Lihat Jadwal Sidang
            Route::get('/lihat-jadwal', [JadwalSidangAkhirController::class, 'index'])->name('jadwal-sidang.read');

            // Lihat Detail Jadwal Sidang
            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])->name('jadwal-sidang.show');

            // Tandai akhir sidang selesai
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
                ->name('jadwal-sidang.mark-done');
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
});


// require __DIR__.'/auth.php'; // Pastikan ini dipanggil
