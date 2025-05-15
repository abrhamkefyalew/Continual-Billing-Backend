<?php

// THIS WAS CREATED BY ME / ABRHAM - [ - NOT used - ] - 
//
//
// it is NOT used BECAUSE   /   i.e. IT IS NOT Used b/c
//         //
//         //
//      created this Middleware MANUALLY in 'app\Http\Middleware' (MUST be created Manually)  // but should NOT be created or used Because In Laravel 12+, the 'auth' middleware alias is automatically registered by default via the framework's internal service provider
//                                    //
//                                    // COMMENTED Because = In Laravel 12+, the 'auth' middleware alias is automatically registered by default via the framework's internal service provider: 
//                                    // Already included internally: so you ALSO do NOT need to create the class MANUALLY = \App\Http\Middleware\Authenticate , unless you explicitly want to OVERRIDE it
//                                                //                      
//                                                // auth()->user();  Therefore is working by default in Laravel 12+, without the need of THE Manually created Middleware = '\App\Http\Middleware\Authenticate'
//
//
// ALSO NEVER Register it in the following two for Laravel 12+ versions
        //
        // 1. do NOT register it in 'bootstrap/app.php' - (for NEWER Laravel 12+ versions)
        //         or   
        // 2. do NOT register it in 'app/Http/Kernel.php' - (incase you override and create 'app/Http/Kernel.php' in this Laravel 12+ version), do NOT register it in 'app/Http/Kernel.php' also




// namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
// use Illuminate\Http\Request;

// class Authenticate extends Middleware
// {
//     /**
//      * Get the path the user should be redirected to when they are not authenticated.
//      */
//     protected function redirectTo(Request $request): ?string
//     {
//         return $request->expectsJson() ? null : route('login');
//     }
// }
