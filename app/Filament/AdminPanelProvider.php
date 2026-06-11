<?php

namespace App\Filament;

use App\Filament\Auth\AdminLogin;
use App\Filament\Pages\Dashboard as AdminDashboard;
use App\Filament\Widgets\AdminStatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login(AdminLogin::class)
            ->authGuard('admin')
            ->brandLogo(fn () => view('filament.logo'))
            ->brandLogoHeight('2.75rem')
            ->favicon('/favicon.ico')
            ->colors([
                'primary' => Color::Teal,
                'danger' => Color::Red,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'info' => Color::Sky,
            ])
            ->font('Inter')
            ->sidebarCollapsibleOnDesktop()
            ->globalSearch()
            ->databaseNotifications()
            ->databaseNotificationsPolling('15s')
            ->spa()
            ->unsavedChangesAlerts()
            ->maxContentWidth('full')
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.custom-styles'),
            )
            ->navigationGroups([
                NavigationGroup::make('Layanan')
                    ->icon('heroicon-o-document-text')
                    ->collapsible(),
                NavigationGroup::make('Data Kependudukan')
                    ->icon('heroicon-o-users')
                    ->collapsible(),
                NavigationGroup::make('Konten')
                    ->icon('heroicon-o-newspaper')
                    ->collapsible(),
                NavigationGroup::make('Pengaturan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->collapsed(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->pages([
                AdminDashboard::class,
            ])
            ->widgets([
                AdminStatsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
