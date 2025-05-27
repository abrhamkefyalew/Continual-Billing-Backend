<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\AssetPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Http\Requests\Api\V1\AdminRequests\StoreAssetPoolRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateAssetPoolRequest;

class AssetPoolController extends Controller
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
    public function store(StoreAssetPoolRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetPool $assetPool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetPoolRequest $request, AssetPool $assetPool)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetPool $assetPool)
    {
        //
    }
}
