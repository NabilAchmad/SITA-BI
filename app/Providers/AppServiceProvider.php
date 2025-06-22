<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator; // Tambahkan di bagian atas jika belum ada
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\User;
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
    public function boot()
    {
        // Gunakan pagination Bootstrap 5
        Paginator::useBootstrapFive(); // atau useBootstrapFour() jika pakai Bootstrap 4

        view()->composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'dosen') {
                $expiresAt = now()->addMinutes(5); // dianggap online selama 5 menit
                Cache::put('user-is-online-' . Auth::id(), true, $expiresAt);
            }
        });

        View::composer('layouts.components.border-admin.profile', function ($view) {
            $admin = User::find(Auth::id());
            $view->with('adminProfile', $admin);
        });

        View::composer('*', function ($view) {
            $view->with('loggedInUser', Auth::user());
        });

        View::composer('layouts.components.border-mahasiswa.profile', function ($view) {
            $user = User::find(Auth::id());

            if ($user) {
                $mahasiswa = $user->mahasiswa; // relasi ke tabel mahasiswa (jika ada)
                $view->with([
                    'userProfile' => $user,
                    'mahasiswa' => $mahasiswa,
                ]);
            }
        });
    }
}
