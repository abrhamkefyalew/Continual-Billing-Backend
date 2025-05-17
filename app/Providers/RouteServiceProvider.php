<?php

// THIS WAS CREATED BY ME / ABRHAM - [ - NOT used - ] - 
//      //
//      // but NOT used b/c Laravel 12+ does NOT use it,
//              //
//              //
//              BECAUSE
//                   // the provider ('app\Providers\RouteServiceProvider.php') is handled by Laravel AUTOMATICALLY = 
//                          // HOW
//                               //
//                               // Routing now defined in bootstrap/app.php    and is Handled by laravel Automatically
//
// COMMENTED and NOT used , because LARAVEL 12+ does NOT need it , b/c laravel handles it automatically
//          // 
//          -   - > i.e. 
//


// namespace App\Providers;

// use Illuminate\Cache\RateLimiting\Limit;
// use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\RateLimiter;
// use Illuminate\Support\Facades\Route;

// class RouteServiceProvider extends ServiceProvider
// {
//     /**
//      * The path to your application's "home" route.
//      *
//      * Typically, users are redirected here after authentication.
//      *
//      * @var string
//      */
//     public const HOME = '/home';

//     /**
//      * Define your route model bindings, pattern filters, and other route configuration.
//      */
//     public function boot(): void
//     {
//         RateLimiter::for('api', function (Request $request) {
//             return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
//         });

//         $this->routes(function () {
//             Route::middleware('api')
//                 ->prefix('api')
//                 ->group(base_path('routes/api.php'));

//             Route::middleware('web')
//                 ->group(base_path('routes/web.php'));
//         });
//     }
// }
