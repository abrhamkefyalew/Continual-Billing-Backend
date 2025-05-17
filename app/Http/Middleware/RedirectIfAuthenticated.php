<?php

// THIS WAS CREATED BY ME / ABRHAM - [ - NOT used - ] - 
//      //
//      // but NOT used b/c Laravel 12+ does NOT use it,
//
// COMMENTED and NOT used , because LARAVEL 12+ does NOT need it
//          // 
//          -   - > i.e. the provider ('app\Providers\RouteServiceProvider.php') does NOT even exist for the NEW Laravel 12+
//

// namespace App\Http\Middleware;

// use App\Providers\RouteServiceProvider;
// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;

// class RedirectIfAuthenticated
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next, string ...$guards): Response
//     {
//         $guards = empty($guards) ? [null] : $guards;

//         foreach ($guards as $guard) {
//             if (Auth::guard($guard)->check()) {
//                 return redirect(RouteServiceProvider::HOME);
//             }
//         }

//         return $next($request);
//     }
// }
