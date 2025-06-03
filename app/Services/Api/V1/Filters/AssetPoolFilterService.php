<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AssetPoolFilterService
{
    public static function applyAssetPoolFilter(Builder $builder, Request $request): Builder
    {
        $filters = [
            'enterprise_id_search' => 'enterprise_id',
            'asset_main_id_search' => 'asset_main_id',
            'payer_id_search' => 'payer_id',
            'directive_id_search' => 'directive_id',
            'penalty_id_search' => 'penalty_id',
            'is_terminated_search' => 'is_terminated',
            'payer_can_terminate_search' => 'payer_can_terminate',
            'is_engaged_search' => 'is_engaged',
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
            'asset_pool_name_search' => 'asset_pool_name',
            'asset_pool_description_search' => 'asset_pool_description',
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
            
            // if (!in_array($paymentStatusSearch, \App\Models\AssetPool::$allowedTypes, true)) {
            //     abort(400, 'Invalid value for payment_status_search');
            // }


            if (in_array($paymentStatusSearch, \App\Models\AssetPool::$allowedTypes, true)) {
                
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
