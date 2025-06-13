<?php

namespace App\Console\Commands;

use App\Models\InvoiceUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\Api\V1\ModelServices\InvoiceUnit\InvoiceUnitService;

class PenaltyUpdateForInvoiceUnit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:penalty-update-for-invoice-unit';
    // protected $signature = 'penalty:update-for-invoice-unit';

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
        Log::info('Command run: app:penalty-update-for-invoice-unit');
        // Log::info('Command run: penalty:update-for-invoice-unit');



        // RUN Penalty update for InvoiceUnit
        (new InvoiceUnitService)->updatePenaltyForMultipleAssetUnitsForAllPayers(InvoiceUnit::whereNotNull('id')->get());
        
    }
}
