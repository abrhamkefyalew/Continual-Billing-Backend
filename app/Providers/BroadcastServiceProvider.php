<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * 
     * 
     * I will use it with
     * Pusher, Laravel Echo, etc.
     * 
     */
    public function boot(): void
    {
        Broadcast::routes();

        // OLD code
        //          // Default Code: from OLD laravel versions
        //
        //          // NOT fault tolerant
        // 
        // require base_path('routes/channels.php');


        // NEW code
        //          // Optional: Defensive Code (Recommended)
        // 
        //          // I You could also make BroadcastServiceProvider more fault-tolerant:
        //                                                                              // This avoids crashing if the file is missing — but Laravel expects the file to be there in a typical setup.
        //
        $channelsFile = base_path('routes/channels.php');
        //
        if (file_exists($channelsFile)) {
            require $channelsFile;
        }


    }
}
