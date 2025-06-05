<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Enterprise;
use Illuminate\Http\Request;
use App\Models\EnterpriseUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\EnterpriseFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreEnterpriseRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateEnterpriseRequest;
use App\Http\Resources\Api\V1\EnterpriseResources\EnterpriseResource;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Enterprise::class);

        $enterprisesBuilder = Enterprise::query();
        $enterprisesBuilder = EnterpriseFilterService::applyEnterpriseFilter($enterprisesBuilder, $request->all());

        $enterprises = $enterprisesBuilder
            ->with(['address'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return EnterpriseResource::collection($enterprises);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEnterpriseRequest $request)
    {
        //
        $var = DB::transaction(function () use ($request) {
            
            // ENTERPRISE code Part

            $enterprise = Enterprise::create([
                'name' => $request['name'],
                'enterprise_description' => $request['enterprise_description'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'is_active' => (int) (isset($request['is_active']) ? $request['is_active'] : 1), 
                'is_approved' => (int) $request->input('is_approved', 1),    // // this column can ONLY be Set by the SUPER_ADMIN, // if Enterprise is registering himself , he can NOT send the is_approved field
                                                                                                   // so this //is_approved// code part will be removed when the Enterprise makes the request
            ]);


            if ($request->has('country') || $request->has('city')) {
                $enterprise->address()->create([
                    'country' => $request->input('country'),
                    'city' => $request->input('city'),
                ]);
            }

            // ENTERPRISE MEDIAs

            // NO enterprise image remove, since it is the first time the enterprise is being stored
            // also use the MediaService class to remove image

            if ($request->has('enterprise_profile_image')) {
                $file = $request->file('enterprise_profile_image');
                $clearMedia = false; // or true // // NO enterprise image remove, since it is the first time the enterprise is being stored
                $collectionName = Enterprise::ENTERPRISE_PROFILE_PICTURE;
                MediaService::storeImage($enterprise, $file, $clearMedia, $collectionName);
            }

            





            // ENTERPRISE USER code Part

            $enterpriseUser = $enterprise->enterpriseUsers()->create([
                'first_name' => $request['user_first_name'],
                'last_name' => $request['user_last_name'],
                'email' => $request['user_email'],
                'password' => $request['user_password'],
                'phone_number' => $request['user_phone_number'],
                'is_active' => (int) (isset($request['user_is_active']) ? $request['user_is_active'] : 1), // 
                'is_admin' => 1,    // the enterprise user stored with the enterprise here (when the enterprise is created for the first time) must always be admin regardless of the user input
            ]);


            if ($request->has('user_country') || $request->has('user_city')) {
                $enterpriseUser->address()->create([
                    'country' => $request->input('user_country'),
                    'city' => $request->input('user_city'),
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





            return EnterpriseResource::make($enterprise->load(['media', 'address', 'enterpriseUsers']));

        });

        return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Enterprise $enterprise)
    {
        // $this->authorize('view', $enterprise);

        return EnterpriseResource::make($enterprise->load(['media', 'address', 'enterpriseUsers']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnterpriseRequest $request, Enterprise $enterprise)
    {
        //
        $var = DB::transaction(function () use ($request, $enterprise) {
            
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
            //
            if ($request->has('enterprise_profile_image')) {
                $file = $request->file('enterprise_profile_image');
                $clearMedia = $request->input('enterprise_profile_image_remove', false);
                $collectionName = Enterprise::ENTERPRISE_PROFILE_PICTURE;
                MediaService::storeImage($enterprise, $file, $clearMedia, $collectionName);
            }

            
            $updatedEnterprise = Enterprise::find($enterprise->id);

            return EnterpriseResource::make($updatedEnterprise->load(['media', 'address', 'enterpriseUsers']));

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
