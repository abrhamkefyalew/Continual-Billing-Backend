<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Payer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\PayerFilterService;
use App\Http\Resources\Api\V1\PayerResources\PayerResource;
use App\Http\Requests\Api\V1\AdminRequests\StorePayerRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdatePayerRequest;

class PayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Payer::class);

        $payersBuilder = Payer::query();
        $payersBuilder = PayerFilterService::applyPayerFilter($payersBuilder, $request->all());

        $payers = $payersBuilder
            ->with(['address'])
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return PayerResource::collection($payers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayerRequest $request)
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
        // $this->authorize('view', $payer);

        return PayerResource::make($payer->load(['media', 'address']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayerRequest $request, Payer $payer)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payer $payer)
    {
        //
    }
}
