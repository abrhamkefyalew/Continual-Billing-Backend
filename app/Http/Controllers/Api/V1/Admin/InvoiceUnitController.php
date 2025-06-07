<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\InvoiceUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\InvoiceUnitFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreInvoiceUnitRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateInvoiceUnitRequest;
use App\Services\Api\V1\ModelServices\InvoiceUnit\InvoiceUnitService;
use App\Http\Resources\Api\V1\InvoiceUnitResources\InvoiceUnitResource;

class InvoiceUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', InvoiceUnit::class);

        

        $invoiceUnitsBuilder = InvoiceUnit::query();


        // HERE WE should call the updatePenalty() Function from service
        //          // updates the UNPAID invoices to calculate additional penalty with the recent date
        //

        $invoiceUnitService = new InvoiceUnitService();

        $penaltyUpdateResult = $invoiceUnitService->updatePenaltyForMultipleAssetUnitsForSinglePayer();

        if ($penaltyUpdateResult['success'] !== true) {
            return response()->json([
                'message' => $penaltyUpdateResult['message'],
                'error' => $penaltyUpdateResult['error'],
            ], 422);
        }

        //
        //


        $invoiceUnitsBuilder = InvoiceUnitFilterService::applyInvoiceUnitFilter($invoiceUnitsBuilder, $request);

        $invoiceUnits = $invoiceUnitsBuilder
            ->with(['assetUnit'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return InvoiceUnitResource::collection($invoiceUnits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceUnitRequest $request)
    {
        //
        $var = DB::transaction(function () {
            
        });

        return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceUnit $invoiceUnit)
    {
        // $this->authorize('view', $invoiceUnit);
        
        return InvoiceUnitResource::make($invoiceUnit->load(['assetUnit']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceUnitRequest $request, InvoiceUnit $invoiceUnit)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceUnit $invoiceUnit)
    {
        //
    }
}
