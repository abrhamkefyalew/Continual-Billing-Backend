<?php

namespace App\Http\Controllers\Api\V1\EnterpriseUser;

use App\Models\Enterprise;
use Illuminate\Http\Request;
use App\Models\EnterpriseUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Http\Resources\Api\V1\EnterpriseResources\EnterpriseResource;
use App\Http\Requests\Api\V1\EnterpriseUserRequests\UpdateEnterpriseRequest;

class EnterpriseController extends Controller
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
        $var = DB::transaction(function () use ($request, $enterprise) {
            
            $user = auth()?->guard()?->user();
            $enterpriseUserLoggedIn = EnterpriseUser::find($user?->id);


            // check if the enterpriseUser is Authorized to update the passed enterpriseUser
            if ($enterpriseUserLoggedIn->is_admin != 1) {
                return response()->json(['message' => 'Unauthorized. you should be enterprise Admin to update enterprise profile'], 401);
            }

            if ($enterpriseUserLoggedIn->enterprise_id != $enterprise->id) {
                return response()->json(['message' => 'invalid Enterprise is selected or Requested. Deceptive request Aborted.'], 403);
            }



            $success = $enterprise->update($request->validated());
            //
            if (!$success) {
                return response()->json(['message' => 'Update Failed'], 500);
            }
            

            if ($request->has('country') || $request->has('city')) {
                if ($enterprise->address) {
                    $enterprise->address()->update([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                } else {
                    $enterprise->address()->create([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                }
            }



            // MEDIA CODE SECTION
            // REMEMBER = (clearMedia) ALL media should NOT be Cleared at once, media should be cleared by id, like one picture. so the whole collection should NOT be cleared using $clearMedia the whole collection // check abrham samson // remember
            //
            if ($request->has('enterprise_profile_image')) {
                $file = $request->file('enterprise_profile_image');
                $clearMedia = $request->input('enterprise_profile_image_remove', false);
                $collectionName = Enterprise::ENTERPRISE_PROFILE_PICTURE;
                MediaService::storeImage($enterprise, $file, $clearMedia, $collectionName);
            }

            
            $updatedEnterprise = Enterprise::find($enterprise->id);

            return EnterpriseResource::make($updatedEnterprise->load('media', 'address', 'contracts' /*, 'orders'*/ , 'enterpriseUsers'));

        });

        return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enterprise $enterprise)
    {
        //
    }
}
