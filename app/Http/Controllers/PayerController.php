<?php

namespace App\Http\Controllers;

use App\Models\Payer;
use App\Http\Requests\StorePayerRequest;
use App\Http\Requests\UpdatePayerRequest;

class PayerController extends Controller
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
    public function store(StorePayerRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Payer $payer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayerRequest $request, Payer $payer)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payer $payer)
    {
        //
    }
}
