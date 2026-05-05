<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Removed custom mapping to let config/database.php handle it natively
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS in production
        if (config('app.env') === 'production' || env('RAILWAY_ENVIRONMENT')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
