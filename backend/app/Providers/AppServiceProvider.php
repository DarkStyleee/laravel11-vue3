<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        $this->configureRateLimiting();
    }

    /**
     * Configure rate limiting.
     */
    protected function configureRateLimiting(): void
    {
        // Standard API rate limiter
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // If needed, you can add additional rate limiters
        // For example, a user-specific rate limiter 'user-api'
        /*
        RateLimiter::for('user-api', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()->id);
        });
        */
    }
}
