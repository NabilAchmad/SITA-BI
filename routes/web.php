<?php

use App\Http\Controllers\Homepage\HomepageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Mahasiswa routes
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\TugasAkhirController;
use App\Http\Controllers\Mahasiswa\PendaftaranSidangController;
use App\Http\Controllers\Mahasiswa\TopikController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;

// Admin routes
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PenugasanPembimbingController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JadwalSidangAkhirController;
use App\Http\Controllers\Admin\JadwalSidangSemproController;

// Dosen routes
use App\Http\Controllers\Dosen\BimbinganMahasiswaController;
use App\Http\Controllers\Dosen\DosenProfileController;
use App\Http\Controllers\Dosen\TawaranTopikController;
use App\Http\Controllers\Dosen\PenilaianSidangController;

//Kaprodi
use App\Http\Controllers\Dosen\Kaprodi\ValidasiController;


// Homepage
Route::get('/', [HomepageController::class, 'index'])->name('home');

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // OTP verification routes
    Route::get('/otp-verify', [AuthController::class, 'showOtpVerificationForm'])->name('auth.otp.verify.form');
    Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify.post');
});

// Rute yang Membutuhkan Autentikasi
Route::middleware(['auth'])->group(function () {
    Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->group(function () {

        // Dashboard Mahasiswa
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.mahasiswa');

        // --- Grup Rute untuk Tugas Akhir ---
        Route::prefix('tugas-akhir')->group(function () {

            // Dashboard utama tugas akhir
            Route::get('/', [TugasAkhirController::class, 'dashboard'])->name('tugas-akhir.dashboard');

            // Halaman progress tugas akhir yang sedang berjalan
            Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugas-akhir.progress');

            Route::post('/upload-ta-proposal', [TugasAkhirController::class, 'uploadProposal'])
                ->name('tugas-akhir.uploadProposal');

            // Menampilkan form untuk mengajukan TA secara mandiri
            Route::get('/ajukan-mandiri', [TugasAkhirController::class, 'ajukanForm'])->name('tugas-akhir.ajukan');

            // Menyimpan pengajuan TA mandiri
            Route::post('/ajukan-mandiri', [TugasAkhirController::class, 'store'])->name('tugasAkhir.store');

            // Menampilkan daftar topik yang ditawarkan dosen
            Route::get('/list-topik', [TopikController::class, 'index'])->name('mahasiswa.topik.index');

            // Memproses saat mahasiswa memilih/mengajukan topik dari dosen
            // Menggunakan Route Model Binding dengan {tawaranTopik}
            Route::post('/ambil-topik/{topik}', [TopikController::class, 'apply'])->name('mahasiswa.topik.ambil');

            // Mengajukan pembatalan tugas akhir
            // Menggunakan Route Model Binding dengan {tugasAkhir}
            Route::post('/{tugasAkhir}/cancel', [TugasAkhirController::class, 'cancel'])->name('tugasAkhir.cancelTA');

            // Menampilkan riwayat tugas akhir yang dibatalkan
            Route::get('/cancelled', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.cancelled');

            // revisi
            Route::get('/revisi', [TugasAkhirController::class, ''])->name('tugas-akhir.revisi');

            // Contoh rute untuk upload file, bisa disesuaikan
            // Route::post('/upload-proposal', [TugasAkhirController::class, 'uploadProposal'])->name('uploadProposal');
        });

        Route::prefix('bimbingan')->group(function () {
            // Tambahkan route untuk Bimbingan di sini jika diperlukan
            Route::get('/', [BimbinganController::class, 'dashboard'])->name('dashboard.bimbingan');

            Route::get('/ajukan-jadwal', [BimbinganController::class, 'ajukanJadwal'])->name('bimbingan.ajukanJadwal');

            Route::post('/store', [BimbinganController::class, 'store'])->name('simpan.jadwal');

            Route::get('/jadwal-bimbingan', [BimbinganController::class, 'jadwalBimbingan'])->name('jadwal.bimbingan');

            Route::put('/bimbingan/jadwal/{id}', [BimbinganController::class, 'ubahJadwal'])->name('bimbingan.updateJadwal');
        });

        Route::prefix('sidang')->group(function () {
            Route::get('dashboard', function () {
                return view('mahasiswa.Sidang.dashboard.dashboard');
            })->name('dashboard.sidang');

            //sempro
            Route::get('/daftar-sempro', [PendaftaranSidangController::class, 'form'])->name('daftar-sempro');
            Route::post('/store', [PendaftaranSidangController::class, 'store'])->name('mahasiswa.sempro.store');

            Route::get('/lihat-nilai', function () {
                return view('mahasiswa.sidang.views.nilaiSidang');
            });

            Route::get('/lihat-jadwal', function () {
                return view('mahasiswa.sidang.views.jadwal');
            });

            Route::get('/daftar-sidang', [PendaftaranSidangController::class, 'form'])->name('pendaftaran_sidang.form');
            Route::post('/daftar-sidang', [PendaftaranSidangController::class, 'store'])->name('pendaftaran_sidang.store');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [MahasiswaProfileController::class, 'profile'])->name('user.profile.mhs');
            Route::put('/update', [MahasiswaProfileController::class, 'update'])->name('user.profile.update.mhs');
        });
    });

    Route::prefix('admin')->middleware(['auth'])->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        });

        Route::prefix('pengumuman')->group(function () {
            // READ
            Route::get('/read', [PengumumanController::class, 'read'])->name('pengumuman.read');

            // CREATE
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

        Route::prefix('mahasiswa')->group(function () {
            Route::prefix('penugasan-pembimbing')->group(function () {

                // Halaman untuk menampilkan mahasiswa yang MEMBUTUHKAN pembimbing.
                Route::get('/belum-pembimbing', [PenugasanPembimbingController::class, 'indexWithoutPembimbing'])
                    ->name('penugasan-bimbingan.index');

                // Halaman untuk menampilkan mahasiswa yang SUDAH memiliki pembimbing.
                Route::get('/list-mahasiswa', [PenugasanPembimbingController::class, 'indexPembimbing'])
                    ->name('list-mahasiswa');

                Route::put('/pilih-pembimbing/{tugasAkhir}', [PenugasanPembimbingController::class, 'store'])
                    ->name('penugasan-bimbingan.store');

                // Rute untuk update juga bisa diarahkan ke method yang sama untuk konsistensi.
                Route::put('/update-pembimbing/{tugasAkhir}', [PenugasanPembimbingController::class, 'update'])
                    ->name('update');
            });
        });

        Route::prefix('kelola-akun')->middleware('permission:manage user accounts')->group(function () {

            // Dosen
            Route::prefix('dosen')->group(function () {
                Route::get('/', [DosenController::class, 'index'])->name('akun-dosen.kelola');

                Route::get('/edit/{dosen}', [DosenController::class, 'edit'])->name('akun-dosen.edit');
                Route::put('/update/{dosen}', [DosenController::class, 'update'])->name('akun-dosen.update');
                Route::delete('/hapus/{dosen}', [DosenController::class, 'destroy'])->name('akun-dosen.destroy');
                Route::post('/tambah-akun-dosen', [DosenController::class, 'store'])->name('akun-dosen.store');
            });

            Route::prefix('mahasiswa')->group(function () {
                // Mahasiswa
                Route::get('/', [MahasiswaController::class, 'index'])->name('akun-mahasiswa.kelola');
                Route::put('/{mahasiswa}', [MahasiswaController::class, 'update'])->name('akun-mahasiswa.update');
                Route::get('/search', [MahasiswaController::class, 'search'])->name('akun-mahasiswa.search');
            });
        });

        Route::prefix('sidang')->group(function () {

            Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dashboard-sidang');

            Route::prefix('akhir')->group(function () {
                // Daftar mahasiswa yang belum punya jadwal sidang akhir
                Route::get('/penjadwalan-sidang-akhir', [JadwalSidangAkhirController::class, 'SidangAkhir'])->name('sidang.kelola.akhir');

                // Simpan data jadwal sidang
                Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])->name('jadwal-sidang.store');

                // Lihat Detail Jadwal Sidang akhir
                Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])->name('jadwal-sidang.show');

                // Edit dan Hapus Jadwal Sidang
                Route::put('/update-jadwal/{jadwal}', [JadwalSidangAkhirController::class, 'update'])->name('jadwal-sidang.update');
                Route::delete('/delete-jadwal/{id}', [JadwalSidangAkhirController::class, 'destroy'])->name('jadwal-sidang.destroy');

                // Tandai akhir sidang selesai 
                Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
                    ->name('jadwal-sidang.mark-done');

                // POST: Simpan dosen penguji
                Route::post('/simpan-penguji/{sidang}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');
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

        // Profile
        Route::prefix('profile')->group(function () {
            Route::get('/', [AdminController::class, 'profile'])->name('user.profile');
            Route::put('/update', [AdminController::class, 'update'])->name('user.profile.update');
        });
    });

    Route::prefix('dosen')->middleware(['auth', 'role:dosen'])->group(function () {

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DosenProfileController::class, 'index_dosen'])->name('dosen.dashboard');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [DosenProfileController::class, 'profile'])->name('user.profile.dosen');
            Route::put('/update', [DosenProfileController::class, 'update'])->name('user.profile.update.dosen');
        });

        Route::prefix('validasi')->middleware(['auth', 'role:kaprodi-d3|kaprodi-d4'])->group(function () {
            Route::get('/tugas-akhir', [ValidasiController::class, 'index'])->name('dosen.validasi-tugas-akhir.index');
            Route::post('/terima/{tugasAkhir}', [ValidasiController::class, 'terima'])->name('dosen.validasi-tugas-akhir.validasi');
            Route::get('/detail/{tugasAkhir}', [ValidasiController::class, 'getDetail'])->name('dosen.validasi-tugas-akhir.detail');
            Route::get('/{tugasAkhir}/cek-kemiripan', [ValidasiController::class, 'cekKemiripan'])->name('dosen.validasi-tugas-akhir.cek-kemiripan');
            Route::post('/tolak/{tugasAkhir}', [ValidasiController::class, 'tolak'])->name('tolak');
        });

        // Validasi nilai sidang akhir: kajur (sudah benar, tidak perlu diubah)
        Route::prefix('validasi-nilai')->middleware(['auth', 'role:kajur'])->group(function () {
            Route::get('/sidang-akhir', [PenilaianSidangController::class, 'index'])->name('dosen.validasi-nilai-tugas-akhir.index');
        });

        // Bimbingan
        Route::prefix('bimbingan')->group(function () {
            Route::get('/', [BimbinganMahasiswaController::class, 'dashboard'])->name('dosen.bimbingan.index');
            Route::get('/detail/{id}', [BimbinganMahasiswaController::class, 'showDetail'])->name('bimbingan.detail');
            Route::prefix('tugas-akhir')->group(function () {
                Route::post('/{tugasAkhir}/setuju-pembatalan', [BimbinganMahasiswaController::class, 'terimaPembatalanTugasAkhir'])->name('setuju-pembatalan-tugas-akhir');
                Route::post('/{tugasAkhir}/tolak-pembatalan', [BimbinganMahasiswaController::class, 'tolakPembatalanTugasAkhir'])->name('tolak-pembatalan-tugas-akhir');
            });
            Route::post('/setujui/{bimbingan}', [BimbinganMahasiswaController::class, 'setujui'])->name('bimbingan.setujui');
            Route::post('/tolak/{bimbingan}', [BimbinganMahasiswaController::class, 'tolakBimbingan'])->name('bimbingan.tolak');
            Route::post('/selesai/{bimbingan}', [BimbinganMahasiswaController::class, 'selesaiBimbingan'])->name('bimbingan.selesai');
            Route::post('/terima-perubahan-jadwal/{perubahan}', [BimbinganMahasiswaController::class, 'terimaPerubahanJadwal'])->name('jadwal.terima');
            Route::post('/tolak-perubahan-jadwal/{perubahan}', [BimbinganMahasiswaController::class, 'tolakPerubahanJadwal'])->name('jadwal.tolak');
        });

        // Route Tawaran Topik
        Route::prefix('tawaran-topik')->middleware(['auth', 'role:dosen'])->group(function () {
            Route::get('/read', [TawaranTopikController::class, 'read'])->name('dosen.tawaran-topik.index');
            Route::post('/create', [TawaranTopikController::class, 'store'])->name('tawaran-topik.create');
            Route::put('/{tawaranTopik}/update', [TawaranTopikController::class, 'update'])->name('dosen.tawaran-topik.update');
            Route::delete('/{tawaranTopik}/soft-delete', [TawaranTopikController::class, 'destroy'])->name('dosen.tawaran-topik.destroy');
            Route::get('/trash', [TawaranTopikController::class, 'trashed'])->name('dosen.tawaran-topik.trashed');
            Route::post('/{id}/restore', [TawaranTopikController::class, 'restore'])->name('dosen.tawaran-topik.restore');
            Route::delete('/{id}/force-delete', [TawaranTopikController::class, 'forceDelete'])->name('dosen.tawaran-topik.force-delete');
            Route::delete('/force-delete-all', [TawaranTopikController::class, 'forceDeleteAll'])->name('dosen.tawaran-topik.force-delete-all');

            Route::post('/dosen/pengajuan-topik/{application}/approve', [TawaranTopikController::class, 'approveApplication'])
                ->name('dosen.tawaran-topik.approveApplication');

            Route::post('/dosen/pengajuan-topik/{application}/reject', [TawaranTopikController::class, 'rejectApplication'])
                ->name('dosen.tawaran-topik.rejectApplication');
        });

        // ROUTE SIDANG
        Route::prefix('sidang')->group(function () {
            Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dosen.sidang.index');
            Route::prefix('sempro')->group(function () {
                Route::get('/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'SidangSempro'])->name('dosen.sidang.kelola.sempro');
                Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])->name('dosen.jadwal-sempro.simpanPenguji');
                Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])->name('dosen.jadwal-sempro.store');
                Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])->name('dosen.jadwal-sempro.show');
                Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])->name('dosen.jadwal-sidang-sempro.mark-done');
            });
            Route::prefix('akhir')->group(function () {
                Route::get('/jadwal-sidang-akhir', [JadwalSidangAkhirController::class, 'sidangAkhir'])->name('dosen.jadwal.sidang.akhir');
                Route::get('/menunggu', [JadwalSidangAkhirController::class, 'sidangAkhir'])->name('dosen.sidang.menunggu.penjadwalan.akhir');
                Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])->name('dosen.jadwal-sidang.store');
                Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])->name('dosen.jadwal-sidang.show');
                Route::put('/update-jadwal/{id}', [JadwalSidangAkhirController::class, 'update'])->name('dosen.jadwal-sidang.update');
                Route::delete('/delete-jadwal/{id}', [JadwalSidangAkhirController::class, 'destroy'])->name('dosen.jadwal-sidang.destroy');
                Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('dosen.jadwal-sidang.simpanPenguji');
                Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])->name('dosen.jadwal-sidang.mark-done');
                Route::prefix('penilaian')->group(function () {
                    Route::get('/sidang', [PenilaianSidangController::class, 'index'])->name('penilaian.sidang.index');
                    Route::get('/sidang/{id}/form', [PenilaianSidangController::class, 'form'])->name('penilaian.sidang.form');
                    Route::post('/sidang/{id}/simpan', [PenilaianSidangController::class, 'simpan'])->name('penilaian.sidang.simpan');
                });
                Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])->name('jadwal-sidang.mark-done');
                Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');
            });
        });
    });
});
