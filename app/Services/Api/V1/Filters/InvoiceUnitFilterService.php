<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InvoiceUnitFilterService
{

    /**
     * Applies filters to the InvoiceUnit query based on request input.
     *
     * @param  Builder  $builder  The query builder instance for model.
     * @param  Request  $request  The HTTP request containing filter parameters.
     * @return Builder            The modified query builder with applied filters.
     */
    public static function applyInvoiceUnitFilter(Builder $builder, Request $request): Builder
    {
        $filters = [
            'asset_unit_id_search' => 'asset_unit_id',
            'transaction_id_system_search' => 'transaction_id_system',
            'transaction_id_banks_search' => 'transaction_id_banks',
            'price_search' => 'price',
            'penalty_search' => 'penalty',
            'immune_to_penalty_search' => 'immune_to_penalty',
            'payment_method_search' => 'payment_method',
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
            'invoice_code_search' => 'invoice_code',
            'start_date_search' => 'start_date',
            'end_date_search' => 'end_date',
            'paid_date_search' => 'paid_date',
            'reason_search' => 'reason',
            'reason_description_search' => 'reason_description',
        ];
        //
        foreach ($likeFilters as $requestKey => $dbField) {
            if (isset($request[$requestKey]) && filled($request[$requestKey])) {
                $builder->where($dbField, 'like', '%' . $request[$requestKey] . '%');
            }
        }

        


        // Validate and apply 'status_search'
        if (isset($request['status_search']) && filled($request['status_search'])) {
            $statusSearch = $request->status_search;
            
            // if (!in_array($statusSearch, \App\Models\InvoiceUnit::allowedTypes(), true)) {
            //     abort(400, 'Invalid value for status_search');
            // }


            if (in_array($statusSearch, \App\Models\InvoiceUnit::allowedTypes(), true)) {
                
                // NO probability this could be NULL, BUT is added Just in case
                if (is_null($statusSearch)) {
                    $builder->whereNull('status');
                } else {
                    $builder->where('status', $statusSearch);
                }

            }

            
        }

        return $builder;
    }
}
