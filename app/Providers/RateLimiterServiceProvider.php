<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class RateLimiterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        // THIS FILE WAS CREATED by ME / ABRHAM
        //
        // THIS Code WAS ADDED BY Me / ABRHAM
        //
        // Created because I want to do RateLimiting / Throttling
        //
        // initially i put this code in AppServiceProvider
        // BUT Now 
        // i took of this RateLimiter Code from AppServiceProvider and put it in this SEPARATE Provider i created
        // as
        // I Created this SEPARATE Provider 
        //                                 // i.e.  = > php artisan make:provider RateLimiterServiceProvider
        //
        //
        //
        // BUT may cause Problems when Called from PROXY servers (i.e. in a BANK setup)
        //          // so COMMENTED until further NOTICE
        //
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
