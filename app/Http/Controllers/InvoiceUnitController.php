<?php

namespace App\Http\Controllers;

use App\Models\InvoiceUnit;
use App\Http\Requests\StoreInvoiceUnitRequest;
use App\Http\Requests\UpdateInvoiceUnitRequest;

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
