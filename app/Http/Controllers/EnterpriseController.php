<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Http\Requests\StoreEnterpriseRequest;
use App\Http\Requests\UpdateEnterpriseRequest;

class EnterpriseController extends Controller
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
    public function store(StoreEnterpriseRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Enterprise $enterprise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnterpriseRequest $request, Enterprise $enterprise)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enterprise $enterprise)
    {
        //
    }
}
