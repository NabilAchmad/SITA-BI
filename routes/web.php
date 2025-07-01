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

Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->group(function () {

    // Dashboard Mahasiswa
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.mahasiswa');

    // --- Grup Rute untuk Tugas Akhir ---
    Route::prefix('tugas-akhir')->group(function () {

        // Dashboard utama tugas akhir
        Route::get('/', [TugasAkhirController::class, 'dashboard'])->name('tugas-akhir.dashboard');

        // Halaman progress tugas akhir yang sedang berjalan
        Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('tugas-akhir.progress');

        // Menampilkan form untuk mengajukan TA secara mandiri
        Route::get('/ajukan-mandiri', [TugasAkhirController::class, 'ajukanForm'])->name('tugas-akhir.ajukan');

        // Menyimpan pengajuan TA mandiri
        Route::post('/ajukan-mandiri', [TugasAkhirController::class, 'store'])->name('tugasAkhir.store');

        // Menampilkan daftar topik yang ditawarkan dosen
        Route::get('/list-topik', [TopikController::class, 'index'])->name('mahasiswa.topik.index');

        // Memproses saat mahasiswa memilih/mengajukan topik dari dosen
        // Menggunakan Route Model Binding dengan {tawaranTopik}
        Route::post('/ambil-topik/{tawaranTopik}', [TopikController::class, 'apply'])->name('topik.apply');

        // Mengajukan pembatalan tugas akhir
        // Menggunakan Route Model Binding dengan {tugasAkhir}
        Route::post('/{tugasAkhir}/cancel', [TugasAkhirController::class, 'cancel'])->name('cancel');

        // Menampilkan riwayat tugas akhir yang dibatalkan
        Route::get('/cancelled', [TugasAkhirController::class, 'showCancelled'])->name('tugasAkhir.cancelled');

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

    Route::prefix('profile')->group(function () {
        Route::get('/', [DosenProfileController::class, 'profile'])->name('user.profile.dosen');
        Route::put('/update', [DosenProfileController::class, 'update'])->name('user.profile.update.dosen');
    });

    // validasi tugas akhir: kaprodi
    Route::prefix('validasi')->middleware(['auth', 'role:kaprodi'])->group(function () {
        Route::get('/tugas-akhir', [ValidasiController::class, 'index'])->name('dosen.validasi-tugas-akhir.index');
        Route::post('/terima/{id}', [ValidasiController::class, 'validasi'])->name('dosen.validasi-tugas-akhir.validasi');
        Route::get('/detail/{id}', [ValidasiController::class, 'detail'])->name('dosen.validasi-tugas-akhir.detail');
        Route::post('/tolak/{id}', [ValidasiController::class, 'tolak'])->name('tolak');
    });

    // Validasi nilai sidang akhir: kajur
    Route::prefix('validasi-nilai')->middleware(['auth', 'role:kajur'])->group(function () {
        Route::get('/sidang-akhir', [PenilaianSidangController::class, 'index'])->name('dosen.validasi-nilai-tugas-akhir.index');
    });

    // Bimbingan
    Route::prefix('bimbingan')->group(function () {
        // Daftar bimbingan
        Route::get('/', [BimbinganMahasiswaController::class, 'dashboard'])->name('dosen.bimbingan.index');

        Route::get('/detail/{id}', [BimbinganMahasiswaController::class, 'showDetail'])->name('bimbingan.detail');

        Route::prefix('tugas-akhir')->group(function () {
            Route::post('/{id}/setuju-pembatalan', [BimbinganMahasiswaController::class, 'terimaPembatalanTugasAkhir'])->name('setuju-pembatalan-tugas-akhir');
            Route::post('/{id}/tolak-pembatalan', [BimbinganMahasiswaController::class, 'tolakPembatalanTugasAkhir'])->name('tolak-pembatalan-tugas-akhir');
        });

        // setujui bimbingan
        Route::post('/setujui/{id}', [BimbinganMahasiswaController::class, 'setujui'])->name('bimbingan.setujui');

        // ROUTE UNTUK TOLAK BIMBINGAN (POST)
        Route::post('/tolak/{id}', [BimbinganMahasiswaController::class, 'tolak'])->name('bimbingan.tolak');

        // tandai bimbingan selesai
        Route::post('/selesai/{id}', [BimbinganMahasiswaController::class, 'selesaiBimbingan'])->name('bimbingan.selesai');

        // terima pengajuan perubahan jadwal bimbingan
        Route::post('/terima-perubahan-jadwal/{id}', [BimbinganMahasiswaController::class, 'terimaJadwal'])
            ->name('jadwal.terima'); // ← perbaikan di sini

        // tolak pengajuan perubahan jadwal bimbingan
        Route::post('/tolak-perubahan-jadwal/{id}', [BimbinganMahasiswaController::class, 'tolakJadwal'])
            ->name('jadwal.tolak'); // ← perbaikan di sini
    });

    // =========================
    // Route Tawaran Topik
    // =========================
    Route::prefix('tawaran-topik')->middleware(['auth', 'role:dosen'])->group(function () {
        // READ
        Route::get('/read', [TawaranTopikController::class, 'read'])->name('dosen.tawaran-topik.index');

        // CREATE
        Route::post('/create', [TawaranTopikController::class, 'store'])->name('tawaran-topik.create');

        // PERBAIKAN: Mengubah parameter dari {id} menjadi {tawaranTopik}
        // agar cocok dengan nama variabel di Controller dan mengaktifkan Route Model Binding.
        // EDIT / UPDATE
        Route::put('/{tawaranTopik}/update', [TawaranTopikController::class, 'update'])->name('dosen.tawaran-topik.update');

        // DELETE (Soft Delete)
        Route::delete('/{tawaranTopik}/soft-delete', [TawaranTopikController::class, 'destroy'])->name('dosen.tawaran-topik.destroy');

        // TRASHED (Manajemen soft delete)
        Route::get('/trash', [TawaranTopikController::class, 'trashed'])->name('dosen.tawaran-topik.trashed');

        // Untuk restore dan force-delete, kita tetap bisa menggunakan {id} karena kita mencarinya secara manual di service.
        // Namun, untuk konsistensi, mengubahnya juga merupakan ide yang baik.
        Route::post('/{id}/restore', [TawaranTopikController::class, 'restore'])->name('dosen.tawaran-topik.restore');
        Route::delete('/{id}/force-delete', [TawaranTopikController::class, 'forceDelete'])->name('dosen.tawaran-topik.force-delete');

        // DELETE ALL (Force delete)
        Route::delete('/force-delete-all', [TawaranTopikController::class, 'forceDeleteAll'])->name('dosen.tawaran-topik.force-delete-all');
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
});
