<?php

namespace App\Http\Controllers\Api\V1\Payer;

use App\Models\Payer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Http\Requests\Api\V1\PayerRequests\UpdatePayerRequest;
use App\Http\Resources\Api\V1\PayerResources\PayerForPayerResource;

class PayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Payer $payer)
    {
        //
        $user = auth()?->guard()?->user();
            
        if ($user?->id != $payer?->id) {
                
            return response()->json(['message' => 'invalid Payer is selected or Requested. Deceptive request Aborted.'], 403);
        }

        return PayerForPayerResource::make($payer->load('media', 'address'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayerRequest $request, Payer $payer)
    {
        //
        $var = DB::transaction(function () use ($request, $payer) {

            $user = auth()?->guard()?->user();
            $payerLoggedIn = Payer::find($user?->id);

            
            if ($payerLoggedIn?->id != $payer?->id) {
                
                return response()->json(['message' => 'invalid Payer is selected or Requested. Deceptive request Aborted.'], 403);
            }
            
            $success = $payer->update($request->validated());
            //
            if (!$success) {
                return response()->json(['message' => 'Update Failed'], 500);
            }
            

            if ($request->has('country') || $request->has('city')) {
                if ($payer?->address) {
                    $payer->address()->update([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                } else {
                    $payer->address()->create([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                }
            }


            if ($request->has('payer_profile_image')) {
                $file = $request->file('payer_profile_image');
                $clearMedia = $request->input('payer_profile_image_remove', false);
                $collectionName = Payer::PAYER_PROFILE_PICTURE;
                MediaService::storeImage($payer, $file, $clearMedia, $collectionName);
            }

            //
            if ($request->has('payer_id_front_image')) {
                $file = $request->file('payer_id_front_image');
                $clearMedia = $request->input('payer_id_front_image_remove', false);
                $collectionName = Payer::PAYER_ID_FRONT_PICTURE;
                MediaService::storeImage($payer, $file, $clearMedia, $collectionName);
            }
            if ($request->has('payer_id_back_image')) {
                $file = $request->file('payer_id_back_image');
                $clearMedia = $request->input('payer_id_back_image_remove', false);
                $collectionName = Payer::PAYER_ID_BACK_PICTURE;
                MediaService::storeImage($payer, $file, $clearMedia, $collectionName);
            }


            $updatedPayer = Payer::find($payer->id);
            
            return PayerForPayerResource::make($updatedPayer->load('media', 'address'));
            
        });

        return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payer $payer)
    {
        //
    }
}
