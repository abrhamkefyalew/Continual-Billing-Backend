<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Carbon\Carbon;
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
                    return response()->json([
                        'message' => 'must send payer id.'
                    ], 400); 
                }
                if (! isset($request['payer_id'])) { 
                    return response()->json([
                        'message' => 'must set payer id.'
                    ], 400); 
                }

                $payer = Payer::find($request['payer_id']);
                //
                abort_if_inactive($payer, 'Payer', $request['payer_id']);
                abort_if_unapproved($payer, 'Payer', $request['payer_id']);


                // Now do operations on each of the orders sent
                //
                // safe() = Uses validated data only, not raw input	
                //
                foreach ($request->safe()->assetUnits as $requestData) {

                    $assetMain = AssetMain::where('id', $requestData['asset_main_id'])->first();
                    //
                    // if ($assetMain?->is_active !== 1) {
                    //     return response()->json(['message' => 'this Asset (asset_main) is NOT Active, please activate the Asset (asset_main) first to make an order.'], 422);
                    // }
                    abort_if_inactive($assetMain, 'AssetMain', $requestData['asset_main_id']);
                    //
                    if ($assetMain?->type !== AssetMain::ASSET_MAIN_OF_ASSET_UNIT_TYPE) {
                        return response()->json([
                            'message' => 'this Asset (asset_main) you are trying to order must be of type ASSET_UNIT_TYPE, i.e. the Asset (asset_main) you selected is NOT an individual Asset'
                        ], 422);
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
                        return response()->json([
                            'message' => 'Enterprise associated with this Asset (asset_main) is missing or has been deleted.'
                        ], 404);
                    }
                    //
                    // if ($assetMain?->enterprise?->is_active !== 1) {
                    //     return response()->json(['message' => 'the Enterprise that owns this Asset (asset_main) is NOT Active, please activate the Enterprise first to make an order.'], 422);
                    // }
                    // //
                    // if ($assetMain?->enterprise?->is_approved !== 1) {
                    //     return response()->json(['message' => 'the Enterprise that owns this Asset (asset_main) has been Unapproved, please approve the Enterprise first to make an order.'], 422);
                    // }
                    //
                    abort_if_inactive($assetMain?->enterprise, 'Enterprise', $assetMain?->enterprise->id);
                    abort_if_unapproved($assetMain?->enterprise, 'Enterprise', $assetMain?->enterprise->id);




                    $directive = Directive::find($requestData['directive_id']);
                    abort_if_inactive($directive, 'Directive', $requestData['directive_id']);

                    $penalty = Penalty::find($requestData['penalty_id']);
                    abort_if_inactive($penalty, 'Penalty', $requestData['penalty_id']);



                    // CHECK REQUEST DATEs (Order dates)

                    // FIRST OF ALL = Check if start_date and end_date are valid dates
                    if (!strtotime($requestData['start_date']) || !strtotime($requestData['end_date'])) {
                        return response()->json(['message' => 'Invalid date format.'], 400);
                    }



                    // order dates // from the request // // Extract and normalize dates from request (strip time)
                    $orderRequestStartDate = Carbon::parse($requestData['start_date'])->startOfDay();
                    $orderRequestEndDate = Carbon::parse($requestData['end_date'])->startOfDay();

                    // todays date  // it should be moved out of the foreach loop // check abrham samson
                    // Get today's date at midnight (00:00:00)
                    $today = Carbon::today(); // Already startOfDay (// Already 00:00:00), but consistent 



                    // order start date = must be today or in the days after today , (i.e. start date can not be before today)
                    // Check if start_date is greater than or equal to today's date
                    if ($orderRequestStartDate->lt($today)) {
                        return response()->json(['message' => 'Order Start date must be greater than or equal to today\'s date.'], 400);
                    }
                    // order end date = must be today or in the days after today , (i.e. end date can not be before today)
                    // Check if end_date is greater than or equal to today's date
                    if ($orderRequestEndDate->lt($today)) {
                        return response()->json(['message' => 'Order End date must be greater than or equal to today\'s date.'], 400);
                    }


                    
                    // request_start_date should be =< request_end_date - for contracts and orders
                    if ($orderRequestStartDate->gt($orderRequestEndDate)) {
                        return response()->json(['message' => 'Order Start Date should not be greater than the Order End Date'], 400);
                    }



                    $assetUnit = AssetUnit::create([
                        'asset_unit_code' => $assetUnitCode,

                        'payer_id' => $request['payer_id'],

                        'enterprise_id' => $assetMain?->enterprise->id,
                        'asset_main_id' => $requestData['asset_main_id'],

                        'directive_id' => $requestData['directive_id'],
                        'penalty_id' => $requestData['penalty_id'],

                        'penalty_starts_after_days' => $requestData['penalty_starts_after_days'],
                        'service_termination_penalty' => $requestData['service_termination_penalty'],
                        'price_principal' => $requestData['price_principal'],
                        'is_payment_by_term_end' => (int) $requestData->input('is_payment_by_term_end', 1),

                        'payment_status' => AssetUnit::ASSET_UNIT_PAYMENT_NOT_STARTED,
                        
            
                        'start_date' => $requestData['start_date'],
                        'end_date' => $requestData['end_date'],

                        'original_end_date' => $requestData['end_date'], // this always holds the end_date of the order as backup, incase the order is terminated.   
                                                                        // if the order is terminated the end_date will be assigned the termination_date.      // So (original_end_date) holds the original order (end_date) as backup

                        'is_terminated' => 0,   // is 0 (false) when order created is initially
                        'payer_can_terminate' => $requestData['payer_can_terminate'],

                        'is_engaged' => 0,
                         
                        'asset_unit_name' => $requestData['asset_unit_name'],
                        'asset_unit_description' => $requestData['asset_unit_description'],
                                                                                    
                    ]);

                    $assetUnitIds[] = $assetUnit->id;
                    
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
