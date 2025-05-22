<?php

namespace App\Http\Controllers;

use App\Models\AssetUnit;
use App\Http\Requests\StoreAssetUnitRequest;
use App\Http\Requests\UpdateAssetUnitRequest;

class AssetUnitController extends Controller
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
    public function store(StoreAssetUnitRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetUnit $assetUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetUnitRequest $request, AssetUnit $assetUnit)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetUnit $assetUnit)
    {
        //
    }
}
