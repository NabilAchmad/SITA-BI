<?php

namespace App\Providers;

// Tambahkan use statement ini
use App\Models\TugasAkhir;
use App\Policies\TugasAkhirPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    // Di dalam AuthServiceProvider.php
    protected $policies = [
        TugasAkhir::class => TugasAkhirPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Baris ini akan mendaftarkan semua policy di atas
        $this->registerPolicies();
    }
}
