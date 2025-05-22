<?php

namespace App\Http\Controllers;

use App\Models\InvoicePool;
use App\Http\Requests\StoreInvoicePoolRequest;
use App\Http\Requests\UpdateInvoicePoolRequest;

class InvoicePoolController extends Controller
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
        //
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
