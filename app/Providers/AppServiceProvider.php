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
        // Force HTTPS in production (Railway)
        if (config('app.env') === 'production' || env('RAILWAY_ENVIRONMENT')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            try {
                // Check if tables exist, if not, build them!
                if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    error_log("DATABASE: No 'users' table found. Starting automatic migration...");
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    error_log("DATABASE: Automatic migration and seeding complete!");
                }
            } catch (\Exception $e) {
                error_log("CRITICAL DATABASE ERROR: " . $e->getMessage());
            }
        }
    }
}
