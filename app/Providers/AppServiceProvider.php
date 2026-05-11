<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
    }

protected function redirectTo()
{
    if (auth()->check()) {
        switch (auth()->user()->role) {
            case 'menagere':
                return route('menagere.dashboard');
            case 'vidangeur':
                return route('vidangeur.dashboard');
            case 'admin':
                return route('dashboard');
            default:
                return route('home');
        }
    }

    return route('home');
}
}