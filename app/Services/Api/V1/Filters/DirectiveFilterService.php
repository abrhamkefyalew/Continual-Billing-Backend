<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DirectiveFilterService
{


    /**
     * Applies filters to the Directive query based on request input.
     *
     * @param  Builder  $builder  The query builder instance for model.
     * @param  Request  $request  The HTTP request containing filter parameters.
     * @return Builder            The modified query builder with applied filters.
     */
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

        


        // Validate and apply 'directive_type_search'
        if (isset($request['directive_type_search']) && filled($request['directive_type_search'])) {
            $directiveTypeSearch = $request->directive_type_search;
            
            // OPTIONAL // since is it checked below also // may be redundant // use this if you want to return error for wrong values sent during filter
            // if (!in_array($directiveTypeSearch, \App\Models\Directive::allowedTypes(), true)) {
            //     abort(400, 'Invalid value for directive_type');
            // }


            if (in_array($directiveTypeSearch, \App\Models\Directive::allowedTypes(), true)) {
                $builder->where('directive_type', $directiveTypeSearch);
            }

            
        }

        return $builder;
    }
}
