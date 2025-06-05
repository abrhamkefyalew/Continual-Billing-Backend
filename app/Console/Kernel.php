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
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            Log::info("scheduler has been run");
            // (new InvoiceUnitService)->updatePenalty(InvoiceUnit::whereNotNull('id')->get());
        })->everyMinute();
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
