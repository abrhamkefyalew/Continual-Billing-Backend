<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //

        // Global middleware that Laravel 12+ does NOT include by default
        $middleware->append([
            \App\Http\Middleware\TrustProxies::class,   //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //   created this Middleware MANUALLY in 'app\Http\Middleware' (MUST be created Manually)
            \App\Http\Middleware\TrimStrings::class,    //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //  //   created this Middleware MANUALLY in 'app\Http\Middleware' (MUST be created Manually)
        ]);

        // Middleware aliases that Laravel 12+ does NOT register by default
        $middleware->alias([

            // 'auth' => \App\Http\Middleware\Authenticate::class,      //  //  //  //  //  //  //  //  //  //  //  //   //   //  created this Middleware MANUALLY in 'app\Http\Middleware' (MUST be created Manually)  // but should NOT be created Because In Laravel 12+, the 'auth' middleware alias is automatically registered by default via the framework's internal service provider
                                    //
                                    // COMMENTED Because = In Laravel 12+, the 'auth' middleware alias is automatically registered by default via the framework's internal service provider: 
                                    // Already included internally: so you ALSO do NOT need to create the class MANUALLY = \App\Http\Middleware\Authenticate , unless you explicitly want to OVERRIDE it
                                                //                      
                                                // auth()->user();  Therefore is working by default in Laravel 12+, without the need of THE Manually created Middleware = '\App\Http\Middleware\Authenticate'


            // 
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,     //  //  //  //  //  //  //  //  //  //  //  //   created this Middleware MANUALLY in 'app\Http\Middleware' (MUST be created Manually)
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,          //  //  //  //  //  //  //  //  //  //  //  //   created this Middleware MANUALLY in 'app\Http\Middleware' (MUST be created Manually)
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // Sanctum-specific middleware
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        ]);

        // Optional group definitions (only needed if customizing)
        // $middleware->group('web', [...]);
        // $middleware->group('api', [...]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
