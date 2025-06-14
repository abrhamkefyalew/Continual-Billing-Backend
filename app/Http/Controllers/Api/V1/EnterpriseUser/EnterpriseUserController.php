<?php

namespace App\Http\Controllers\Api\V1\EnterpriseUser;

use Illuminate\Http\Request;
use App\Models\EnterpriseUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Http\Resources\Api\V1\EnterpriseUserResources\EnterpriseUserResource;
use App\Http\Requests\Api\V1\EnterpriseUserRequests\StoreEnterpriseUserRequest;
use App\Http\Requests\Api\V1\EnterpriseUserRequests\UpdateEnterpriseUserRequest;

class EnterpriseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = auth()?->guard()?->user();
        $enterpriseUser = EnterpriseUser::find($user?->id);
        
        $enterprise = EnterpriseUser::where('enterprise_id', $enterpriseUser->enterprise_id);
        
        $enterpriseData = $enterprise->with(['media', 'address', 'enterprise'])->latest()->paginate(FilteringService::getPaginate($request));       // this get multiple enterpriseUsers of the enterprise

        return EnterpriseUserResource::collection($enterpriseData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnterpriseUserRequest $request)
    {
        //
        $var = DB::transaction(function () use ($request) {
            
            $user = auth()?->guard()?->user();
            $enterpriseUserLoggedIn = EnterpriseUser::find($user?->id);

            // check if the enterpriseUser is enterprise admin
            if ($enterpriseUserLoggedIn->is_admin !== 1) {
                return response()->json(['message' => 'UnAuthorized. you are not enterprise Admin'], 401); 
            }
            
            
            $enterpriseUser = EnterpriseUser::create([
                'enterprise_id' => $enterpriseUserLoggedIn->enterprise_id,
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'password' => $request['password'],
                'phone_number' => $request['phone_number'],
                'is_active' => (int) (isset($request['is_active']) ? $request['is_active'] : 1), // this works
                'is_admin' => (int) $request->input('is_admin', 0), // this works also
            ]);


            if ($request->has('country') || $request->has('city')) {
                $enterpriseUser->address()->create([
                    'country' => $request->input('country'),
                    'city' => $request->input('city'),
                ]);
            }

            
            // ENTERPRISE USER MEDIAs

            // NO enterprise_user image remove, since it is the first time the enterprise_user is being stored
            // also use the MediaService class to remove image

            if ($request->has('enterprise_user_profile_image')) {
                $file = $request->file('enterprise_user_profile_image');
                $clearMedia = false; // or true // // NO enterprise_user image remove, since it is the first time the enterprise_user is being stored
                $collectionName = EnterpriseUser::ENTERPRISE_USER_PROFILE_PICTURE;
                MediaService::storeImage($enterpriseUser, $file, $clearMedia, $collectionName);
            }


            return EnterpriseUserResource::make($enterpriseUser->load(['media', 'address', 'enterprise']));
            
        });

        return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(EnterpriseUser $enterpriseUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnterpriseUserRequest $request, EnterpriseUser $enterpriseUser)
    {
        //
        $var = DB::transaction(function () use ($request, $enterpriseUser) {

            $user = auth()?->guard()?->user();
            $enterpriseUserLoggedIn = EnterpriseUser::find($user?->id);


            // check if the enterpriseUser is Authorized to update the passed enterpriseUser
            if ($enterpriseUserLoggedIn?->is_admin != 1) {

                if ($enterpriseUserLoggedIn?->id != $enterpriseUser?->id) {
                
                    return response()->json(['message' => 'invalid Enterprise User is selected or Requested. Deceptive request Aborted.'], 403);
                }

            } else if ($enterpriseUserLoggedIn?->is_admin == 1) {

                if ($enterpriseUserLoggedIn?->enterprise_id != $enterpriseUser?->enterprise_id) {
                
                    return response()->json(['message' => 'invalid Enterprise User is selected or Requested by Enterprise Admin. Deceptive request Aborted.'], 403);
                }

            } else {
                return response()->json(['message' => 'Invalid Enterprise Role.'], 404); 
            }
            
            

            
            $success = $enterpriseUser->update($request->validated());
            //
            if (!$success) {
                return response()->json(['message' => 'Update Failed'], 500);
            }
            

            if ($request->has('country') || $request->has('city')) {
                if ($enterpriseUser->address) {
                    $enterpriseUser->address()->update([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                } else {
                    $enterpriseUser->address()->create([
                        'country' => $request->input('country'),
                        'city' => $request->input('city'),
                    ]);
                }
            }



            // MEDIA CODE SECTION
            // REMEMBER = (clearMedia) ALL media should NOT be Cleared at once, media should be cleared by id, like one picture. so the whole collection should NOT be cleared using $clearMedia the whole collection // check abrham samson // remember
            //
            if ($request->has('enterprise_user_profile_image')) {
                $file = $request->file('enterprise_user_profile_image');
                $clearMedia = $request->input('enterprise_user_profile_image_remove', false);
                $collectionName = EnterpriseUser::ENTERPRISE_USER_PROFILE_PICTURE;
                MediaService::storeImage($enterpriseUser, $file, $clearMedia, $collectionName);
            }

            
            $updatedEnterpriseUser = EnterpriseUser::find($enterpriseUser->id);

            return EnterpriseUserResource::make($updatedEnterpriseUser->load(['media', 'address', 'enterprise']));

        });

        return $var;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnterpriseUser $enterpriseUser)
    {
        //
    }
}
