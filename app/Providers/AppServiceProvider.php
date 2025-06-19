<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator; // Tambahkan di bagian atas jika belum ada
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\User;

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
            $user = User::find(20); // Ambil user yang sedang login

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
