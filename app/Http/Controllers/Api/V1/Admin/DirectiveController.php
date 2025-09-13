<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Directive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Api\V1\MediaService;
use App\Services\Api\V1\FilteringService;
use App\Services\Api\V1\Filters\DirectiveFilterService;
use App\Http\Requests\Api\V1\AdminRequests\StoreDirectiveRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateDirectiveRequest;
use App\Http\Resources\Api\V1\DirectiveResources\DirectiveResource;

class DirectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Directive::class);

        $directivesBuilder = Directive::query();
        $directivesBuilder = DirectiveFilterService::applyDirectiveFilter($directivesBuilder, $request);

        $directives = $directivesBuilder
            ->latest()
            ->paginate(FilteringService::getPaginate($request));

        return DirectiveResource::collection($directives);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectiveRequest $request)
    {
        //
        $var = DB::transaction(function () use ($request) {
            
            $directive = Directive::create($request->validated());

            return DirectiveResource::make($directive);

        });

        return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Directive $directive)
    {
        // $this->authorize('view', $directive);
        
        return DirectiveResource::make($directive);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectiveRequest $request, Directive $directive)
    {
        //
        $var = DB::transaction(function () use ($request, $directive) {

            $directive->update($request->validated());

            return DirectiveResource::make($directive);
            
        });

        return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Directive $directive)
    {
        //
    }
}
