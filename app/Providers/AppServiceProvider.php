<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('discord', \SocialiteProviders\Discord\Provider::class);
        });

        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

//         $target = storage_path('app/public');
//         $link = public_path('storage');
//
//         if (!File::exists($link) || !is_link($link)) {
//             Artisan::call('storage:link');
//         }

        Filament::registerNavigationGroups([
            'Comics',
            'Administrativo',
        ]);
    }
}
