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
            ->with(['address', 'media', 'enterprise'])
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
        $var = DB::transaction(function () use($request) {

            $assetMain = AssetMain::create([
                'enterprise_id' => $request['enterprise_id'],
                'asset_name' => $request['asset_name'],
                'asset_description' => $request['asset_description'],
                'type' => $request->input('type', AssetMain::ASSET_MAIN_OF_ASSET_UNIT_TYPE),
                'is_active' => (int) $request->input('is_active', 1),                                                                        
            ]);



            // if the assetMain have an actual location , where it is currently located
            if ($request->has('country') || $request->has('city')) {
                $assetMain->address()->create([
                    'country' => $request->input('country'),
                    'city' => $request->input('city'),
                ]);
            }


            if ($request->has('asset_profile_image')) {
                $file = $request->file('asset_profile_image');
                $clearMedia = false; // or true // // NO assetMain image remove, since it is the first time the assetMain is being stored
                $collectionName = AssetMain::ASSET_MAIN_PROFILE_PICTURE;
                MediaService::storeImage($assetMain, $file, $clearMedia, $collectionName);
            }

            return AssetMainResource::make($assetMain->load(['address', 'media', 'enterprise']));
            
        });

        return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetMain $assetMain)
    {
        // $this->authorize('view', $assetMain);
        
        return AssetMainResource::make($assetMain->load(['address', 'media', 'enterprise']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssetMainRequest $request, AssetMain $assetMain)
    {
        //
        $var = DB::transaction(function () use ($request, $assetMain) {

            $success = $assetMain->update($request->validated());
            //
            if (!$success) {
                return response()->json(['message' => 'Update Failed'], 500);
            }


            if ($request->has('country') || $request->has('city')) {
                if ($assetMain->address) {
                    $assetMain->address()->update([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                } else {
                    $assetMain->address()->create([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                }
            }



            if ($request->has('asset_profile_image')) {
                $file = $request->file('asset_profile_image');
                $clearMedia = (isset($request['asset_profile_image_remove']) ? $request['asset_profile_image_remove'] : false);
                $collectionName = AssetMain::ASSET_MAIN_PROFILE_PICTURE;
                MediaService::storeImage($assetMain, $file, $clearMedia, $collectionName);
            }


            $updatedAssetMain = AssetMain::find($assetMain->id);


            return AssetMainResource::make($updatedAssetMain->load(['address', 'media', 'enterprise']));
            
        });

        return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetMain $assetMain)
    {
        //
    }
}
