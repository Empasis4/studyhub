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
        // Force-map Railway variables to Laravel config
        if (env('MYSQLHOST')) {
            config([
                'database.connections.mysql.host' => env('MYSQLHOST'),
                'database.connections.mysql.port' => env('MYSQLPORT'),
                'database.connections.mysql.database' => env('MYSQLDATABASE'),
                'database.connections.mysql.username' => env('MYSQLUSER'),
                'database.connections.mysql.password' => env('MYSQLPASSWORD'),
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Automatically run migrations in production (Railway)
        if (config('app.env') === 'production' || env('RAILWAY_ENVIRONMENT')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {
                // Fail silently to allow app to boot
            }
        }
    }
}
