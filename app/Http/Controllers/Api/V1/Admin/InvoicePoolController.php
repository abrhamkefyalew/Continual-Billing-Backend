<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\InvoicePool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\InvoicePoolFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreInvoicePoolRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateInvoicePoolRequest;
use App\Http\Resources\Api\V1\InvoicePoolResources\InvoicePoolResource;

class InvoicePoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', InvoicePool::class);

        $invoicePoolsBuilder = InvoicePool::query();
        $invoicePoolsBuilder = InvoicePoolFilterService::applyInvoicePoolFilter($invoicePoolsBuilder, $request);

        $invoicePools = $invoicePoolsBuilder
            ->with(['assetPool', 'payer'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return InvoicePoolResource::collection($invoicePools);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoicePoolRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoicePool $invoicePool)
    {
        // $this->authorize('view', $invoicePool);
        
        return InvoicePoolResource::make($invoicePool->load(['assetPool', 'payer']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoicePoolRequest $request, InvoicePool $invoicePool)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoicePool $invoicePool)
    {
        //
    }
}
