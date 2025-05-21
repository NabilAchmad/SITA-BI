<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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
        view()->composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'dosen') {
                $expiresAt = now()->addMinutes(5); // dianggap online selama 5 menit
                Cache::put('user-is-online-' . Auth::id(), true, $expiresAt);
            }
        });
    }
}
