<?php

namespace App\Console\Commands;

use App\Models\InvoicePool;
use App\Models\InvoiceUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\Api\V1\ModelServices\InvoiceUnit\InvoiceUnitService;

class RunPenaltyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-penalty-update';
    // protected $signature = 'penalty:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Log::info('Command run: app:run-penalty-update');
        // Log::info('Command run: penalty:update');



        // RUN Penalty update for InvoiceUnit
        (new InvoiceUnitService)->updatePenaltyForMultipleAssetUnitsForAllPayers(InvoiceUnit::whereNotNull('id')->get());

        // RUN PEnalty update for InvoicePool
        // (new InvoicePoolService)->updatePenaltyForMultipleAssetUnitsForAllPayers(InvoicePool::whereNotNull('id')->get());
    }
}
