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
        // Force HTTPS and Nuclear Database Hijack in production (Railway)
        if (config('app.env') === 'production' || env('RAILWAY_ENVIRONMENT')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            // Nuclear Hijack: Parse the Master URL if available
            $masterUrl = env('MYSQL_PRIVATE_URL') ?: env('MYSQL_URL') ?: env('DATABASE_URL');
            if ($masterUrl && strpos($masterUrl, 'mysql') !== false) {
                $components = parse_url($masterUrl);
                config([
                    'database.connections.mysql.host' => $components['host'] ?? config('database.connections.mysql.host'),
                    'database.connections.mysql.port' => $components['port'] ?? config('database.connections.mysql.port'),
                    'database.connections.mysql.database' => ltrim($components['path'], '/') ?: 'studyhub_db',
                    'database.connections.mysql.username' => $components['user'] ?? 'studyhub_db',
                    'database.connections.mysql.password' => $components['pass'] ?? '',
                ]);
            }
            
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
