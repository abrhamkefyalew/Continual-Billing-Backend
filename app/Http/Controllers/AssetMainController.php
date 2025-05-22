<?php

namespace App\Http\Controllers;

use App\Models\AssetMain;
use App\Http\Requests\StoreAssetMainRequest;
use App\Http\Requests\UpdateAssetMainRequest;

class AssetMainController extends Controller
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
    public function store(StoreAssetMainRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetMain $assetMain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetMainRequest $request, AssetMain $assetMain)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetMain $assetMain)
    {
        //
    }
}
