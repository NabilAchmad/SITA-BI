<?php

use Illuminate\Support\Facades\Route;

// Import semua controller di satu tempat agar rapi
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Homepage\HomepageController;
// Panel Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\JadwalSidangAkhirController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\PengumumanController;
// Panel Jurusan (Kajur & Kaprodi)
use App\Http\Controllers\Dosen\Kaprodi\ValidasiController;
use App\Http\Controllers\Admin\PenugasanPembimbingController;
// Panel Dosen
use App\Http\Controllers\Dosen\BimbinganMahasiswaController;
use App\Http\Controllers\Dosen\DosenProfileController;
use App\Http\Controllers\Dosen\PenilaianSidangController;
use App\Http\Controllers\Dosen\TawaranTopikController;
use App\Http\Controllers\Dosen\JadwalBimbinganController;
use App\Http\Controllers\Dosen\CatatanBimbinganController;
// Panel Mahasiswa
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\PendaftaranSidangController;
use App\Http\Controllers\Mahasiswa\TopikController;
use App\Http\Controllers\Mahasiswa\TugasAkhirController;
use App\Http\Controllers\Mahasiswa\CatatanBimbinganController as MahasiswaCatatanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute dirombak dengan mengelompokkannya ke dalam "Panel" berdasarkan peran.
| Struktur ini lebih bersih, aman, dan mudah dikelola.
|
*/

//======================================================================
// RUTE PUBLIK (Tidak Perlu Login)
//======================================================================
Route::get('/', [HomepageController::class, 'index'])->name('home');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Rute registrasi dan OTP bisa ditambahkan di sini jika masih relevan
    // OTP verification routes
    Route::get('/otp-verify', [AuthController::class, 'showOtpVerificationForm'])->name('otp.verify.form');
    Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.post');
    // TAMBAHKAN ROUTE INI
    Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

// Tambahkan alias route "login" default Laravel
Route::get('/login', fn() => redirect()->route('auth.login'))->name('login');

//======================================================================
// RUTE YANG MEMBUTUHKAN AUTENTIKASI
//======================================================================
Route::middleware(['auth'])->group(function () {

    //------------------------------------------------------------------
    // PANEL MAHASISWA
    //------------------------------------------------------------------
    Route::prefix('mahasiswa')->middleware('role:mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [MahasiswaProfileController::class, 'show'])->name('profile');
        Route::put('/profile/update', [MahasiswaProfileController::class, 'update'])->name('profile.update');

        Route::prefix('tugas-akhir')->name('tugas-akhir.')->group(function () {
            Route::get('/', [TugasAkhirController::class, 'dashboard'])->name('dashboard');
            Route::get('/progress', [TugasAkhirController::class, 'progress'])->name('progress');
            Route::get('/ajukan-mandiri', [TugasAkhirController::class, 'ajukanForm'])->name('ajukan');
            Route::post('/ajukan-mandiri', [TugasAkhirController::class, 'store'])->name('store');
            Route::post('/{tugasAkhir}/upload-file', [TugasAkhirController::class, 'ajukanBimbingan'])->name('upload-file');

            // Rute untuk MELIHAT halaman riwayat tugas akhir yang dibatalkan.
            Route::get('/riwayat-pembatalan', [TugasAkhirController::class, 'showCancelled'])->name('show-cancelled');

            Route::get('/list-topik', [TopikController::class, 'index'])->name('topik.index');
            Route::post('/ambil-topik/{topik}', [TopikController::class, 'apply'])->name('topik.ambil');
            Route::get('/cancel', [TugasAkhirController::class, 'showCancelled'])->name('show.cancel');
            Route::post('/{tugasAkhir}/cancel', [TugasAkhirController::class, 'cancel'])->name('cancel');

            Route::post('tugas-akhir/{tugasAkhir}/catatan', [MahasiswaCatatanController::class, 'store'])->name('catatan.store');
        });

        Route::prefix('bimbingan')->name('bimbingan.')->group(function () {
            Route::get('/', [BimbinganController::class, 'dashboard'])->name('dashboard');
            Route::get('/ajukan-jadwal', [BimbinganController::class, 'ajukanJadwal'])->name('ajukanJadwal');
            Route::post('/store', [BimbinganController::class, 'store'])->name('store');
            Route::get('/jadwal-bimbingan', [BimbinganController::class, 'jadwalBimbingan'])->name('jadwal');
            Route::put('/jadwal/{id}', [BimbinganController::class, 'ubahJadwal'])->name('updateJadwal');
        });

        Route::prefix('sidang')->name('sidang.')->group(function () {
            Route::get('/', [PendaftaranSidangController::class, 'dashboard'])->name('dashboard');
            Route::get('/daftar-sempro', [PendaftaranSidangController::class, 'form'])->name('daftar-sempro');
            Route::post('/store-sempro', [PendaftaranSidangController::class, 'store'])->name('store-sempro');
            Route::get('/daftar-sidang-akhir', [PendaftaranSidangController::class, 'form'])->name('daftar-akhir');
            Route::post('/store-sidang-akhir', [PendaftaranSidangController::class, 'store'])->name('store-akhir');
        });
    });

    //------------------------------------------------------------------
    // PANEL DOSEN (Umum)
    //------------------------------------------------------------------
    Route::prefix('dosen')->middleware('role:dosen|kajur|kaprodi-d3|kaprodi-d4')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenProfileController::class, 'index_dosen'])->name('dashboard');
        Route::get('/profile', [DosenProfileController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [DosenProfileController::class, 'update'])->name('profile.update');

        // Grup untuk halaman utama bimbingan
        Route::prefix('bimbingan-mahasiswa')->name('bimbingan.')->group(function () {
            // Halaman dasbor menampilkan daftar mahasiswa
            Route::get('/', [BimbinganMahasiswaController::class, 'index'])->name('index');

            // Halaman detail/pusat komando untuk satu mahasiswa
            Route::get('/show/{tugasAkhir}', [BimbinganMahasiswaController::class, 'show'])->name('show');
        });

        // Grup untuk aksi-aksi spesifik terkait bimbingan
        Route::prefix('tugas-akhir/{tugasAkhir}')->group(function () {
            Route::post('/jadwal', [JadwalBimbinganController::class, 'store'])->name('jadwal.store');

            // Route untuk menambah catatan/feedback baru
            Route::post('/catatan', [CatatanBimbinganController::class, 'store'])->name('catatan.store');

            // routes/web.php
            Route::post('/bimbingan/{bimbingan}/selesai', [JadwalBimbinganController::class, 'selesaikan'])->name('jadwal.selesai');
            // routes/web.php

            // ✅ PERBAIKAN: Route untuk membatalkan sesi. Perhatikan {bimbingan} bukan {tugasAkhir}
            Route::post('/bimbingan/{bimbingan}/cancel', [JadwalBimbinganController::class, 'cancel'])->name('jadwal.cancel');

            // (Nantinya route untuk persetujuan sidang bisa ditambahkan di sini)
            // Route::post('/setujui-sidang', [PersetujuanSidangController::class, 'store'])->name('sidang.approve');
        });

        Route::prefix('tawaran-topik')->name('tawaran-topik.')->group(function () {
            Route::get('/trash', [TawaranTopikController::class, 'trashed'])->name('trashed');
            Route::post('/{id}/restore', [TawaranTopikController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [TawaranTopikController::class, 'forceDelete'])->name('force-delete');
            Route::delete('/force-delete-all', [TawaranTopikController::class, 'forceDeleteAll'])->name('force-delete-all');
            Route::post('/approve/{application}', [TawaranTopikController::class, 'approveApplication'])->name('approveApplication');
            Route::post('/reject/{application}', [TawaranTopikController::class, 'rejectApplication'])->name('rejectApplication');
        });

        Route::resource('tawaran-topik', TawaranTopikController::class)->except(['show']);
        // Rute tambahan untuk tawaran topik
        Route::post('tawaran-topik/approve/{application}', [TawaranTopikController::class, 'approveApplication'])->name('tawaran-topik.approve');
        Route::post('tawaran-topik/reject/{application}', [TawaranTopikController::class, 'rejectApplication'])->name('tawaran-topik.reject');
    });

    //------------------------------------------------------------------
    // PANEL JURUSAN (Kajur & Kaprodi)
    //------------------------------------------------------------------
    Route::prefix('jurusan')->middleware('role:admin|kajur|kaprodi-d3|kaprodi-d4')->name('jurusan.')->group(function () {
        Route::prefix('validasi-judul')->name('validasi-judul.')->group(function () {
            Route::get('/', [ValidasiController::class, 'index'])->name('index');
            Route::post('/terima/{tugasAkhir}', [ValidasiController::class, 'terima'])->name('terima');
            Route::post('/tolak/{tugasAkhir}', [ValidasiController::class, 'tolak'])->name('tolak');
            Route::get('/detail/{tugasAkhir}', [ValidasiController::class, 'getDetail'])->name('detail');

            // ✅ TAMBAHKAN RUTE INI
            // Rute ini akan menangani panggilan AJAX untuk mengecek kemiripan judul.
            Route::get('/{tugasAkhir}/cek-kemiripan', [ValidasiController::class, 'cekKemiripan'])->name('cek-kemiripan');
        });

        Route::prefix('penugasan-pembimbing')->name('penugasan-pembimbing.')->group(function () {
            Route::get('/belum-ditugaskan', [PenugasanPembimbingController::class, 'indexWithoutPembimbing'])->name('index');
            Route::get('/sudah-ditugaskan', [PenugasanPembimbingController::class, 'indexPembimbing'])->name('sudah');
            Route::put('/store/{tugasAkhir}', [PenugasanPembimbingController::class, 'store'])->name('store');
            Route::put('/update/{tugasAkhir}', [PenugasanPembimbingController::class, 'update'])->name('update');
        });

        Route::prefix('penjadwalan-sidang')->name('penjadwalan-sidang.')->group(function () {
            Route::get('/', [JadwalSidangAkhirController::class, 'dashboard'])->name('index');
            Route::get('/detail', [JadwalSidangAkhirController::class, 'SidangAkhir'])->name('detail');
            Route::post('/', [JadwalSidangAkhirController::class, 'store'])->name('store');
            Route::get('/{sidang}', [JadwalSidangAkhirController::class, 'show'])->name('show');
            Route::put('/{sidang}', [JadwalSidangAkhirController::class, 'update'])->name('update');
            Route::delete('/{sidang}', [JadwalSidangAkhirController::class, 'destroy'])->name('destroy');
            Route::post('/{sidang}/simpan-penguji', [JadwalSidangAkhirController::class, 'simpanPenguji'])->name('simpan-penguji');
        });
    });

    //------------------------------------------------------------------
    // PANEL ADMIN (Sistem)
    //------------------------------------------------------------------
    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [AdminController::class, 'update'])->name('profile.update');

        Route::prefix('kelola-akun')->name('akun.')->group(function () {
            Route::resource('mahasiswa', AdminMahasiswaController::class)->except(['create', 'show', 'edit']);
            Route::resource('dosen', AdminDosenController::class)->except(['create', 'show', 'edit']);
        });

        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            // Rute untuk menampilkan halaman data yang sudah di-soft delete
            Route::get('/trash', [PengumumanController::class, 'trashed'])->name('trashed');

            // Rute untuk mengembalikan satu data dari trash
            Route::post('/{id}/restore', [PengumumanController::class, 'restore'])->name('restore');

            // Rute untuk menghapus satu data secara permanen dari trash
            Route::delete('/{id}/force-delete', [PengumumanController::class, 'forceDelete'])->name('force-delete');

            // Rute untuk menghapus semua data di dalam trash secara permanen
            Route::delete('/force-delete-all', [PengumumanController::class, 'forceDeleteAll'])->name('force-delete-all');
        });

        Route::resource('pengumuman', PengumumanController::class);
        // Rute tambahan untuk pengumuman (trash, restore, dll.) bisa ditambahkan di sini
        // Tambahkan grup ini untuk semua rute kustom terkait pengumuman

        Route::get('/laporan', [LaporanController::class, 'show'])->name('laporan.index');
        Route::get('/log-aktivitas', [LogController::class, 'index'])->name('log.index');
    });
});
