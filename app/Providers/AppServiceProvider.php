<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Inertia::share([
            'locales' => fn() => config('app.allowed_locales'),
            'locale' => fn() => App::getLocale(),
            'translations' => fn() => File::exists(resource_path("lang/" . App::getLocale() . ".json")) ? File::json(resource_path("lang/" . App::getLocale() . ".json")) : [],
        ]);
    }
}
