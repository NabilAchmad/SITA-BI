<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator; // Tambahkan di bagian atas jika belum ada
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\User;

// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Pagination\Paginator; // Tambahkan di bagian atas jika belum ada
// use Carbon\Carbon;
// use Illuminate\Support\Facades\View;
// use App\Models\User;
use App\Http\Controllers\Controller;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kirim data user yang sedang login ke view profil di header
        View::composer('layouts.components.border-mahasiswa.profile', function ($view) {
            $user = User::find(2); // Ambil user yang sedang login

            if ($user) {
                $mahasiswa = $user->mahasiswa; // relasi ke tabel mahasiswa (jika ada)
                $view->with([
                    'userProfile' => $user,
                    'mahasiswa' => $mahasiswa,
                ]);
            }
        });

        // Gunakan pagination Bootstrap 5
        Paginator::useBootstrapFive(); // atau useBootstrapFour() jika pakai Bootstrap 4

        view()->composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'dosen') {
                $expiresAt = now()->addMinutes(5); // dianggap online selama 5 menit
                Cache::put('user-is-online-' . Auth::id(), true, $expiresAt);
            }
        });

        View::composer('layouts.components.border-admin.profile', function ($view) {
            $admin = User::find(1); // pastikan admin dengan id 21 ada
            $view->with('adminProfile', $admin);
        });
    }
}
