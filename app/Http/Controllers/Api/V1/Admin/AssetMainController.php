<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\AssetMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\AssetMainFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreAssetMainRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateAssetMainRequest;
use App\Http\Resources\Api\V1\AssetMainResources\AssetMainResource;

class AssetMainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', AssetMain::class);

        // $assetMainsBuilder = AssetMain::whereNotNull('id');

        $assetMainsBuilder = AssetMain::query();
        $assetMainsBuilder = AssetMainFilterService::applyAssetMainFilter($assetMainsBuilder, $request->all());

        $assetMains = $assetMainsBuilder
            ->with(['enterprise', 'address'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return AssetMainResource::collection($assetMains);

        
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
        // $this->authorize('view', $assetMain);
        
        return AssetMainResource::make($assetMain->load(['media', 'address', 'enterprise']));
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
