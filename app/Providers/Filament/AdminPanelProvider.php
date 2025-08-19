<?php

namespace App\Providers\Filament;

use App\Livewire\Widgets\StatusChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\Permission\Middleware\RoleMiddleware;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Utils\Settings;
use CWSPS154\AppSettings\AppSettingsPlugin as AppSettingsAppSettingsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->spa()
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Neutral,
                'info' => Color::Blue,
                'primary' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->font('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap')
            ->brandLogo(asset('images/logo_white.svg'))
            ->favicon(asset('images/logo_white.svg'))
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // StatusChart::class,
                // \BezhanSalleh\FilamentGoogleAnalytics\Widgets\PageViewsWidget::class,
            ])
            ->plugins([
                // \BezhanSalleh\FilamentGoogleAnalytics\FilamentGoogleAnalyticsPlugin::make(),
                FilamentSpatieRolesPermissionsPlugin::make(),
                AppSettingsAppSettingsPlugin::make()
                    ->canAccessAppSectionTab(function () {
                        return false;
                    }),
                \TomatoPHP\FilamentBrowser\FilamentBrowserPlugin::make()
                    ->hiddenFolders([
                        base_path('public/storage'),
                        base_path('public/js'),
                        base_path('public/css'),
                        base_path('public/build')
                    ])
                    ->hiddenFiles([
                        base_path('public/.htaccess')
                    ])
                    ->hiddenExtensions([
                        "php"
                    ])
                    ->allowCreateFolder()
                    ->allowEditFile()
                    ->allowCreateNewFile()
                    ->allowCreateFolder()
                    ->allowRenameFile()
                    ->allowDeleteFile()
                    ->allowMarkdown()
                    ->allowCode()
                    ->allowPreview()
                    ->basePath(base_path('public')),
            ])
            ->brandName(Settings::find('app.app_name', 'Manga Reader'))
            ->sidebarFullyCollapsibleOnDesktop(true)
            ->sidebarCollapsibleOnDesktop(true)
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
                // RoleMiddleware::class . ':admin|editor',
            ]);
    }
}
