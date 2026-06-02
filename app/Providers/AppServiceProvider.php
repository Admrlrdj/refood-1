<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken; // <-- Ubah path ini menuju model yang baru kita buat

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
        // Beritahu Sanctum untuk memakai model token custom kita
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
