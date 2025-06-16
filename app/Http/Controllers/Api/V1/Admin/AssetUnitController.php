<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Payer;
use App\Models\Penalty;
use App\Models\AssetMain;
use App\Models\AssetUnit;
use App\Models\Directive;
use Illuminate\Support\Str;
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
        $var = DB::transaction(function () use ($request) {
            
            if ($request->has('assetUnits')) {

                // abrham samson check // check abrham samson
                // here check all the sent contract_detail_id s in the request belonged to the same payer_id sent in the request
                
                
                $assetUnitIds = [];
                    // since multiple orders can be sent at once 
                        // i will put similar asset_unit_code in OrderController = for those multiple orders that are sent at once
                        //
                // Generate a random order code
                $assetUnitCode = Str::random(20); // Adjust the length as needed

                // Check if the generated code already exists in the database
                while (AssetUnit::where('asset_unit_code', $assetUnitCode)->exists()) {
                    $assetUnitCode = Str::random(20); // Regenerate the code if it already exists
                }

                
                // i think this two if conditions are checked and validated by the FormRequest=StoreOrderRequest // so it may be duplicate // but check first
                if (! $request->has('payer_id')) {
                    return response()->json(['message' => 'must send payer id.'], 400); 
                }
                if (! isset($request['payer_id'])) { 
                    return response()->json(['message' => 'must set payer id.'], 400); 
                }

                $payer = Payer::find($request['payer_id']);

                if ($payer?->is_approved !== 1) {
                    return response()->json(['message' => 'this payer has been Unapproved, please approve the payer first to make an order'], 422);
                }

                if ($payer?->is_active !== 1) {
                    return response()->json(['message' => 'this payer is NOT Active, please activate the payer first to make an order.'], 422); 
                }


                // Now do operations on each of the orders sent
                //
                // safe() = Uses validated data only, not raw input	
                //
                foreach ($request->safe()->assetUnits as $requestData) {

                    $assetMain = AssetMain::where('id', $requestData['asset_main_id'])->first();
                    //
                    if ($assetMain?->is_active !== 1) {
                        return response()->json(['message' => 'this Asset (asset_main) is NOT Active, please activate the Asset (asset_main) first to make an order.'], 422);
                    }
                    //
                    if ($assetMain?->type !== AssetMain::ASSET_MAIN_OF_ASSET_UNIT_TYPE) {
                        return response()->json(['message' => 'this Asset (asset_main) you are trying to order must be of type ASSET_UNIT_TYPE, i.e. the Asset (asset_main) you selected is NOT an individual Asset'], 422);
                    }


                    // if (! $enterprise = $assetMain->enterprise) {
                    //     return response()->json(['message' => 'Enterprise associated with this asset is missing or has been deleted.'], 404);
                    // }
                    // if ($enterprise->is_active !== 1) {
                    //     return response()->json(['message' => 'the Enterprise that owns this Asset (asset_main) is NOT Active, please activate the Enterprise first to make an order.'], 422);
                    // }
                    // if ($enterprise->is_approved !== 1) {
                    //     return response()->json(['message' => 'the Enterprise that owns this Asset (asset_main) has been Unapproved, please approve the Enterprise first to make an order.'], 422);
                    // }

                    if (! $assetMain->enterprise) {
                        return response()->json(['message' => 'Enterprise associated with this Asset (asset_main) is missing or has been deleted.'], 404);
                    }
                    //
                    if ($assetMain?->enterprise?->is_active !== 1) {
                        return response()->json(['message' => 'the Enterprise that owns this Asset (asset_main) is NOT Active, please activate the Enterprise first to make an order.'], 422);
                    }
                    //
                    if ($assetMain?->enterprise?->is_approved !== 1) {
                        return response()->json(['message' => 'the Enterprise that owns this Asset (asset_main) has been Unapproved, please approve the Enterprise first to make an order.'], 422);
                    }


                    $directive = Directive::find($requestData['directive_id']);
                    //
                    if ($directive?->is_active !== 1) {
                        return response()->json(['message' => 'this Directive you selected is NOT Active, please activate the directive first to make an order.'], 422);
                    }

                    $penalty = Penalty::find($requestData['penalty_id']);
                    //
                    if ($penalty?->is_active !== 1) {
                        return response()->json(['message' => 'this Penalty you selected is NOT Active, please activate the penalty first to make an order.'], 422);
                    }



                    
                }

                // WORKS
                $assetUnits = AssetUnit::whereIn('id', $assetUnitIds)->with(['enterprise', 'assetMain', 'payer', 'directive', 'penalty'])->latest()->get();       // this get the AssetUnit orders created here, should NOT be paginate // must be get()
                return AssetUnitResource::collection($assetUnits);
            
            }

        });

        return $var;
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
