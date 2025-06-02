<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DirectiveFilterService
{
    public static function applyDirectiveFilter(Builder $builder, Request $request): Builder
    {
        $filters = [
            'type_search' => 'type',
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

        


        // Validate and apply 'payment_status_search'
        if (isset($request['payment_status_search']) && filled($request['payment_status_search'])) {
            $paymentStatusSearch = $request->payment_status_search;
            
            // if (!in_array($paymentStatusSearch, \App\Models\AssetUnit::allowedTypes(), true)) {
            //     abort(400, 'Invalid value for payment_status_search');
            // }


            if (in_array($paymentStatusSearch, \App\Models\AssetUnit::allowedTypes(), true)) {
                
                if (is_null($paymentStatusSearch)) {
                    $builder->whereNull('payment_status');
                } else {
                    $builder->where('payment_status', $paymentStatusSearch);
                }

            }

            
        }

        return $builder;
    }
}
