<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DirectiveFilterService
{
    public static function applyDirectiveFilter(Builder $builder, Request $request): Builder
    {
        $filters = [
            'is_active_search' => 'is_active',
        ];
        //
        // Direct match filters
        foreach ($filters as $requestKey => $dbField) {
            if (isset($request[$requestKey]) && $request->filled($requestKey)) {
                $builder->where($dbField, $request[$requestKey]);
            }
        }

        


        // Partial match (LIKE) filters
        $likeFilters = [
            'name_search' => 'name',
        ];
        //
        foreach ($likeFilters as $requestKey => $dbField) {
            if (isset($request[$requestKey]) && filled($request[$requestKey])) {
                $builder->where($dbField, 'like', '%' . $request[$requestKey] . '%');
            }
        }

        


        // Validate and apply 'type_search'
        if (isset($request['type_search']) && filled($request['type_search'])) {
            $typeSearch = $request->type_search;
            
            // if (!in_array($typeSearch, \App\Models\AssetUnit::allowedTypes(), true)) {
            //     abort(400, 'Invalid value for type_search');
            // }


            if (in_array($typeSearch, \App\Models\Directive::allowedTypes(), true)) {
                $builder->where('type', $typeSearch);
            }

            
        }

        return $builder;
    }
}
