<?php

return [

    // REGISTER PROVIDERs Here

    

    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
        //
        //
        // AuthServiceProvider	- REQUIRED  	-FOR using policies	    - Needed BECAUSE I define model-policy mappings or override auth logic
        //                      //
        //                      // Needed for
        //                      //          = > App\Policies, like the AdminPolicy, PayerPolicy,  InvoicePolicy  . . .  that I always use.
        //
        //
        // App\Policies\. . . Policy.php = > requires that I DEFINE and REGISTER AuthServiceProvider.
        //
        //                                  So I absolutely do need it.




    App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RateLimiterServiceProvider::class,

    
    // App\Providers\RouteServiceProvider::class,
    //
    // THIS WAS WRITTEN  - & -  COMMENTED BY ME / ABRHAM - [ - NOT used - ] - 
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
    // COMMENTED and NOT used , because LARAVEL 12+ does NOT need  , b/c laravel handles it automatically
    //          // 
    //          -   - > i.e. 
    //
];
