<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Enterprise;
use Illuminate\Http\Request;
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
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enterprise $enterprise)
    {
        //
    }
}
