<?php

namespace App\Console;

use App\Models\InvoiceUnit;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\Api\V1\ModelServices\InvoiceUnit\InvoiceUnitService;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * 
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * 
     * @return void
     * 
     */
    protected function schedule(Schedule $schedule): void
    {
        
        
        /*
        // NOT used for NOW
        //
        // for Laravel 12+, these below commented codes does NOT work
        // INSTEAD they are transferred to 'route/console.php'
        //      as for LARAVEL 12+ the following code are fit to work if they are in 'route/console.php'


        // $schedule->command('inspire')->hourly();


        
        $schedule->command('app:run-penalty-update')->everyMinute();
        // $schedule->command('penalty:update')->everyMinute();


        
        // $schedule->call(function () {
        //     Log::info("scheduler has been run");
        //     // (new InvoiceUnitService)->updatePenalty(InvoiceUnit::whereNotNull('id')->get());
        // })->everyMinute();

        */

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
