<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\EnterpriseUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\EnterpriseUserFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreEnterpriseUserRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateEnterpriseUserRequest;
use App\Http\Resources\Api\V1\EnterpriseUserResources\EnterpriseUserResource;

class EnterpriseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', EnterpriseUser::class);

        $enterpriseUsersBuilder = EnterpriseUser::query();
        $enterpriseUsersBuilder = EnterpriseUserFilterService::applyEnterpriseUserFilter($enterpriseUsersBuilder, $request->all());

        $enterpriseUsers = $enterpriseUsersBuilder
            ->with(['media', 'address', 'enterprise'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return EnterpriseUserResource::collection($enterpriseUsers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnterpriseUserRequest $request)
    {
        //
        $var = DB::transaction(function () use ($request) {
            
            $enterpriseUser = EnterpriseUser::create([
                'enterprise_id' => $request['enterprise_id'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'password' => $request['password'],
                'phone_number' => $request['phone_number'],
                'is_active' => (int) (isset($request['is_active']) ? $request['is_active'] : 1), //
                'is_admin' => (int) $request->input('is_admin', 0), // 
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
        // $this->authorize('view', $enterpriseUser);

        return EnterpriseUserResource::make($enterpriseUser->load(['media', 'address', 'enterprise']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnterpriseUserRequest $request, EnterpriseUser $enterpriseUser)
    {
        //
        $var = DB::transaction(function () use ($request, $enterpriseUser) {
            
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
        // $this->authorize('delete', $enterpriseUser);

        $var = DB::transaction(function () use ($enterpriseUser) {

            if (AuditTrail::where('enterprise_user_id', $enterpriseUser->id)->exists()) {
                
                // this works
                // return response()->json([
                //     'message' => 'Cannot delete the EnterpriseUser because it is in use by AuditTrails.',
                // ], 409);

                // this also works
                return response()->json([
                    'message' => 'Cannot delete the EnterpriseUser because it is in use by AuditTrails.'
                ], Response::HTTP_CONFLICT);
            }

            $enterpriseUser->delete();

            return response()->json(true, 200);

        });

        return $var;
    }



    public function restore(string $id)
    {
        $enterpriseUser = EnterpriseUser::withTrashed()->find($id);

        $this->authorize('restore', $enterpriseUser);

        $var = DB::transaction(function () use ($enterpriseUser) {
            
            if (!$enterpriseUser) {
                abort(404);    
            }
    
            $enterpriseUser->restore();
    
            return response()->json(true, 200);

        });

        return $var;
        
    }

    


}
