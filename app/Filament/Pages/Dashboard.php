<?php

namespace App\Filament\Pages;

use App\Livewire\Widgets\StatusChart;
use Filament\Pages\Page;
use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.my-custom-dashboard-page';

    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationGroup = 'Administrativo';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasPermissionTo('view_dashboard');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatusChart::class,
            PulseServers::class,
            PulseCache::class,
            PulseExceptions::class,
            PulseUsage::class,
            PulseSlowQueries::class,
            PulseSlowRequests::class,
            PulseSlowOutGoingRequests::class,
        ];
    }
}
