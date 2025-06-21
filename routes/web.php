<?php

use App\Http\Controllers\Homepage\HomepageController;

use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\TugasAkhirController;
use App\Http\Controllers\Mahasiswa\PendaftaranSidangController;
use App\Http\Controllers\Mahasiswa\TopikController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;

use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PenugasanPembimbingController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JadwalSidangAkhirController;
use App\Http\Controllers\Admin\JadwalSidangSemproController;

use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Dosen\TawaranTopikController;
use App\Http\Controllers\Dosen\PenilaianSidangController;
use App\Http\Controllers\Dosen\DosenProfileController;
use App\Http\Controllers\Dosen\BimbinganMahasiswaController;


// Homepage
Route::get('/', [HomepageController::class, 'index'])->name('home');

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->group(function () {

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

        Route::put('/bimbingan/jadwal/{id}', [BimbinganController::class, 'ubahJadwal'])->name('bimbingan.updateJadwal');

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

    Route::prefix('profile')->group(function () {
        Route::get('/', [MahasiswaProfileController::class, 'profile'])->name('user.profile.mhs');
        Route::put('/update', [MahasiswaProfileController::class, 'update'])->name('user.profile.update.mhs');
    });
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

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

        // Update pembimbing (edit via modal)
        Route::put('/update-pembimbing/{tugasAkhirId}', [PenugasanPembimbingController::class, 'update'])->name('pembimbing.update');
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

        Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dashboard-sidang');

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

    // bimbingan
    Route::prefix('bimbingan')->group(function () {
        // Daftar bimbingan
        Route::get('/', [BimbinganMahasiswaController::class, 'dashboard'])->name('dosen.bimbingan.index');

        Route::get('/belumMulai', [BimbinganController::class, 'ajukanJadwal'])->name('dosen.bimbingan.crud-bimbingan.ajukan.jadwal');

        Route::get('/sedangBerlangsung', [BimbinganController::class, 'lihatBimbingan'])->name('dosen.bimbingan.crud-bimbingan.lihat.bimbingan');

        // ROUTE UNTUK AJUKAN PERUBAHAN JADWAL (EDIT JADWAL)
        Route::get('/menungguReview', function () {
            return view('admin.bimbingan.crud-bimbingan.ajukan-perubahan');
        })->name('dosen.bimbingan.crud-bimbingan.ajukan.perubahan');

        // ROUTE UNTUK TOLAK BIMBINGAN (POST)
        Route::post('/tolak', [BimbinganController::class, 'tolak'])->name('dosen.bimbingan.tolak');
    });


    // =========================
    // Route Tawaran Topik
    // =========================

    Route::prefix('tawaran-topik')->group(function () {
        // READ
        Route::get('/read', [TawaranTopikController::class, 'read'])->name('dosen.tawaran-topik.index');

        // CREATE
        Route::get('/create', [TawaranTopikController::class, 'create'])->name('TawaranTopik.form'); // Form tambah
        Route::post('/create', [TawaranTopikController::class, 'store'])->name('TawaranTopik.create'); // Simpan data baru

        // EDIT / UPDATE
        Route::get('/{id}/edit', [TawaranTopikController::class, 'edit'])->name('TawaranTopik.edit'); // Form edit
        Route::put('/{id}/update', [TawaranTopikController::class, 'update'])->name('TawaranTopik.update'); // Update data

        // DELETE (Soft Delete)
        Route::delete('/{id}/soft-delete', [TawaranTopikController::class, 'destroy'])->name('TawaranTopik.destroy'); // Soft delete

        // DELETE ALL (Force delete)
        Route::delete('/force-delete-all', [TawaranTopikController::class, 'forceDeleteAll'])->name('TawaranTopik.force-delete-all');

        // TRASHED (Manajemen soft delete)
        Route::get('/trash', [TawaranTopikController::class, 'trashed'])->name('TawaranTopik.trashed'); // Tampilkan data terhapus
        Route::post('/{id}/restore', [TawaranTopikController::class, 'restore'])->name('TawaranTopik.restore'); // Restore data
        Route::delete('/{id}/force-delete', [TawaranTopikController::class, 'forceDelete'])->name('TawaranTopik.force-delete'); // Hapus permanen
    });

    // =========================
    // ROUTE Mahasiswa
    // =========================

    Route::prefix('mahasiswa')->group(function () {
        // Daftar mahasiswa belum punya pembimbing
        Route::get('/belum-pembimbing', [PenugasanPembimbingController::class, 'indexWithOutPembimbing'])->name('dosen.penugasan-bimbingan.index');

        // Form pilih pembimbing untuk mahasiswa tertentu
        Route::get('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'create'])->name('dosen.penugasan-bimbingan.create');
        Route::post('/pilih-pembimbing/{id}', [PenugasanPembimbingController::class, 'store'])->name('dosen.penugasan-bimbingan.store');

        // Daftar mahasiswa sudah punya pembimbing
        Route::get('/list-mahasiswa', [PenugasanPembimbingController::class, 'indexPembimbing'])->name('dosen.list-mahasiswa');
    });

    // =========================
    // ROUTE SIDANG
    // =========================
    Route::prefix('sidang')->group(function () {

        Route::get('dashboard-sidang', [JadwalSidangAkhirController::class, 'dashboard'])->name('dosen.sidang.index');

        Route::prefix('sempro')->group(function () {

            Route::get('/jadwal-sidang-sempro', [JadwalSidangSemproController::class, 'SidangSempro'])
                ->name('dosen.sidang.kelola.sempro'); // ← perbaikan di sini

            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangSemproController::class, 'simpanPenguji'])
                ->name('dosen.jadwal-sempro.simpanPenguji');

            Route::post('/jadwal-sidang', [JadwalSidangSemproController::class, 'store'])
                ->name('dosen.jadwal-sempro.store');

            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'show'])
                ->name('dosen.jadwal-sempro.show');

            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangSemproController::class, 'tandaiSidangSempro'])
                ->name('dosen.jadwal-sidang-sempro.mark-done');
        });


        Route::prefix('akhir')->group(function () {

            // ✅ Halaman utama kelola jadwal sidang akhir (semua tab: menunggu, jadwal, lulus, tidak lulus)
            Route::get('/jadwal-sidang-akhir', [JadwalSidangAkhirController::class, 'sidangAkhir'])
                ->name('dosen.jadwal.sidang.akhir');

            // ✅ Alias tambahan (opsional) jika Blade masih memanggil route ini
            Route::get('/menunggu', [JadwalSidangAkhirController::class, 'sidangAkhir'])
                ->name('dosen.sidang.menunggu.penjadwalan.akhir');

            // ✅ Simpan data jadwal sidang
            Route::post('/jadwal-sidang', [JadwalSidangAkhirController::class, 'store'])
                ->name('dosen.jadwal-sidang.store');

            // ✅ Lihat detail jadwal sidang akhir
            Route::get('/detail-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'show'])
                ->name('dosen.jadwal-sidang.show');

            // ✅ Update & hapus jadwal sidang
            Route::put('/update-jadwal/{id}', [JadwalSidangAkhirController::class, 'update'])
                ->name('dosen.jadwal-sidang.update');
            Route::delete('/delete-jadwal/{id}', [JadwalSidangAkhirController::class, 'destroy'])
                ->name('dosen.jadwal-sidang.destroy');

            // ✅ Simpan penguji
            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])
                ->name('dosen.jadwal-sidang.simpanPenguji');

            // ✅ Tandai status sidang
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
                ->name('dosen.jadwal-sidang.mark-done');

            // ============================
            // 5. Routes nilai sidang
            // ============================

            Route::prefix('penilaian')->group(function () {
                Route::get('/sidang', [PenilaianSidangController::class, 'index'])->name('penilaian.sidang.index');
                Route::get('/sidang/{id}/form', [PenilaianSidangController::class, 'form'])->name('penilaian.sidang.form');
                Route::post('/sidang/{id}/simpan', [PenilaianSidangController::class, 'simpan'])->name('penilaian.sidang.simpan');
            });

            // Tandai akhir sidang selesai 
            Route::post('/tandai-sidang/{sidang_id}', [JadwalSidangAkhirController::class, 'tandaiSidang'])
                ->name('jadwal-sidang.mark-done');

            // Daftar mahasiswa yang sudah sidang akhir


            // POST: Simpan dosen penguji
            Route::post('/simpan-penguji/{sidang_id}', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('jadwal-sidang.simpanPenguji');
        });
    });

    // Admin: Laporan dan Statistik
    Route::prefix('/laporan')->name('laporan.')->group(function () {
        // Lihat laporan dan statistik
        Route::get('/lihat', [LaporanController::class, 'show'])
            ->name('statistik.dosen');
    });

    // Admin: Logs
    Route::prefix('/logs')->name('log.')->group(function () {
        // Lihat log aktivitas sistem
        Route::get('/lihat', [LogController::class, 'index'])
            ->name('aktifitas');
    });

    //tugas akhir

    Route::prefix('ta')->group(function () {
        // 1. Halaman dashboard TA
        Route::get('/dashboard', [TugasAkhirController::class, 'dashboard'])->name('ta.dashboard');

        // 2. Kirim komentar revisi (dari modal revisi)
        Route::post('/revisi', [TugasAkhirController::class, 'revisiStore'])->name('ta.revisi');

        // 3. Aksi ACC dan Tolak per revisi
        Route::post('/acc/{id}', [TugasAkhirController::class, 'acc'])->name('ta.acc');
        Route::post('/tolak/{id}', [TugasAkhirController::class, 'tolak'])->name('ta.tolak');
    });


    // Profile
    Route::view('/profile', 'admin/user/views/profile')->name('dosen.user.profile');
});
