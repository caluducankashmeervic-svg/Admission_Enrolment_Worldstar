<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Ensure storage symlink exists (needed on fresh deployments / Laravel Cloud)
        if (! app()->runningInConsole() && ! file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }
    }
}
