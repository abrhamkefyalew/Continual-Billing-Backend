<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\InvoiceUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Http\Requests\Api\V1\AdminRequests\StoreInvoiceUnitRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateInvoiceUnitRequest;

class InvoiceUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceUnitRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceUnit $invoiceUnit)
    {
        //
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
