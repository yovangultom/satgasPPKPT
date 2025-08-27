<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pengaduan;
use App\Observers\PengaduanObserver;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\PengaduanResource;
use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;
use Filament\Support\Assets\Css;


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
        Pengaduan::observe(PengaduanObserver::class);
        View::composer('layouts.navigation', NotificationComposer::class);
        FilamentAsset::register([
            Css::make('custom-filament-theme', Vite::asset('resources/css/filament/admin/theme.css')),
        ]);
    }
}
