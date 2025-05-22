<?php

namespace App\Http\Controllers;

use App\Models\Penalty;
use App\Http\Requests\StorePenaltyRequest;
use App\Http\Requests\UpdatePenaltyRequest;

class PenaltyController extends Controller
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
    public function store(StorePenaltyRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Penalty $penalty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenaltyRequest $request, Penalty $penalty)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penalty $penalty)
    {
        //
    }
}
