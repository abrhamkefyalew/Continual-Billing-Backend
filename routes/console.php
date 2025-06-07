<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');






// ===================================================================================================================== SCHEDULE ===================================================================================================================== //





// -------------------------------------------------------------------- SCHEDULE CALL (Command based) --------------------------------------------------------------------//

// $schedule->command('inspire')->hourly();


//
// STATIC Call
Schedule::command('app:run-penalty-update')->everyMinute();
// Schedule::command('penalty:update')->everyMinute();

// 
// NON Static call  - CHECK First 
//
// $schedule = new Schedule();
// $schedule->command('app:run-penalty-update')->everyMinute();
// $schedule->command('penalty:update')->everyMinute();


// -------------------------------------------------------------------- end SCHEDULE CALL (Command based) --------------------------------------------------------------------//







// -------------------------------------------------------------------- SCHEDULE CALL (Closure based) --------------------------------------------------------------------//


// STATIC Call
Schedule::call(function () {
    
    Log::info("scheduler has been run");
    // (new InvoiceUnitService)->updatePenalty(InvoiceUnit::whereNotNull('id')->get());
    
})->everyTenMinutes();



// closure-based schedule at specified time
// Schedule::call(function () {
//     Log::info('Closure scheduled task ran.');
// })->dailyAt('10:00');

// -------------------------------------------------------------------- end SCHEDULE CALL (Closure based) --------------------------------------------------------------------//




// ===================================================================================================================== end SCHEDULE ===================================================================================================================== //



