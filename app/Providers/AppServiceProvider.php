<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator; // Tambahkan di bagian atas jika belum ada
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\TawaranTopik;
use App\Http\Controllers\Controller;
use App\Models\TugasAkhir;          // <-- Pastikan ada
use App\Policies\TugasAkhirPolicy; // <-- Pastikan ada

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        TugasAkhir::class => TugasAkhirPolicy::class, // <-- Pastikan baris ini ada
    ];

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

        // Profile Admin
        View::composer('layouts.components.border-admin.profile', function ($view) {
            $admin = User::find(Auth::id());
            $view->with('adminProfile', $admin);
        });

        // Profile Dosen
        View::composer('layouts.components.border-dosen.profile', function ($view) {
            $user = User::find(Auth::id());

            $view->with('userProfile', $user);
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

        View::composer('*', function ($view) {
            $view->with('loggedInUser', Auth::user());
        });

        // Komponen untuk menampilkan topik tugas akhir di homepage
        View::composer('layouts.components.content-homepage.tawarantopik', function ($view) {
            $today = now()->format('Y-m-d'); // atau Date::now()->toDateString();
            $cacheKey = 'topik_tugas_akhir_' . $today;

            $topikTugasAkhir = Cache::remember($cacheKey, now()->endOfDay()->diffInSeconds(now()), function () {
                return \App\Models\TawaranTopik::with('user')
                    ->latest()
                    ->take(5)
                    ->get();
            });

            $view->with('topikTugasAkhir', $topikTugasAkhir);
        });
    }
}
