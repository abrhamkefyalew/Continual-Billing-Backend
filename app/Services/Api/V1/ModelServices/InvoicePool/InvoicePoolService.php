<?php

namespace App\Services\Api\V1\ModelServices\InvoicePool;

use Carbon\Carbon;
use App\Models\Penalty;
use App\Models\AssetPool;
use App\Models\InvoicePool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class InvoicePoolService
{


    /**
     * Penalty will only be generated/Updated for the already generated invoices/bills ONLY (those already generaged  Bills/invoices should also be UNPAID invoices)
     * 
     * so NO new invoice/bill will be generated here
     * 
     * Penalty will be Updated for
     *      1. AssetPools  -> with payment_status = (PAYMENT_STARTED & PAYMENT_LAST)
     *      1. Invoices    -> with status         = (NOT_PAID)
     * 
     */
    public function updatePenaltyForMultipleAssetPoolsForAllPayers(InvoicePool $invoicePool)
    {



    }


}

