<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\UpdateLastLoginAt;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        Event::listen(
            Login::class,
            UpdateLastLoginAt::class
        );

        RateLimiter::for('license_api', function (Request $request) {
            $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
            $key = $apiKey ?? $request->ip();

            return [
                Limit::perMinute(100)->by($key),
                Limit::perDay(1000)->by($key),
            ];
        });
    }
}
