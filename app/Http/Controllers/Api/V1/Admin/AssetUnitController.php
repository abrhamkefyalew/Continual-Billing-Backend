<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\AssetUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\AssetUnitFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreAssetUnitRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateAssetUnitRequest;
use App\Http\Resources\Api\V1\AssetUnitResources\AssetUnitResource;

class AssetUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', AssetUnit::class);

        $assetUnitsBuilder = AssetUnit::query();
        $assetUnitsBuilder = AssetUnitFilterService::applyAssetUnitFilter($assetUnitsBuilder, $request);

        $assetUnits = $assetUnitsBuilder
            ->with(['enterprise', 'assetMain', 'payer', 'directive', 'penalty'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return AssetUnitResource::collection($assetUnits);
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
        // $this->authorize('view', $assetUnit);
        
        return AssetUnitResource::make($assetUnit->load(['enterprise', 'assetMain', 'payer', 'directive', 'penalty']));
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
