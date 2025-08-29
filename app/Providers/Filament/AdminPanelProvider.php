<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Resources\PengaduanResource\Widgets\PengaduanStatsOverview;
use App\Filament\Widgets\RektorStatsOverview;
use App\Filament\Widgets\HtlStatsOverview;
use App\Filament\Pages\EditProfile;
use Filament\Navigation\MenuItem;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->sidebarWidth('16rem')
            ->id('admin')
            ->path('admin')
            ->login()
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->url(fn(): string => EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            ->brandLogo(asset('images/Logo PPKPT 2025 Square Black - CROP.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->favicon('images/Logo PPKPT 2025 Square Black - CROP.png')
            ->darkMode(false)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                EditProfile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                PengaduanStatsOverview::class,
                RektorStatsOverview::class,
                HtlStatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                VerifyCsrfToken::class,
                DispatchServingFilamentEvent::class,
            ])
            ->unsavedChangesAlerts()
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureUserHasRole::class,
            ]);
        // ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
