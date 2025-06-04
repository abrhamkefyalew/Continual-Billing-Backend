<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;

class PenaltyFilterService
{

    /**
     * Applies filters to the Penalty query based on request input.
     *
     * @param  Builder  $builder
     * @param  array    $filters
     * @return Builder
     */
    public static function applyPenaltyFilter(Builder $builder, array $filters): Builder
    {
        return $builder
            ->when(isset($filters['percent_of_principal_price_search']) && filled($filters['percent_of_principal_price_search']), fn($q) =>
                $q->where('percent_of_principal_price', $filters['percent_of_principal_price_search']))
            ->when(isset($filters['is_active_search']) && filled($filters['is_active_search']), fn($q) =>
                $q->where('is_active', $filters['is_active_search']))
            ->when(isset($filters['penalty_type_search']) && filled($filters['penalty_type_search']), function ($q) use ($filters) {
                $penaltyTypeSearch = $filters['penalty_type_search'];


                // OPTIONAL // since is it checked below also // may be redundant // use this if you want to return error for wrong values sent during filter
                // if (!in_array($penaltyTypeSearch, \App\Models\Penalty::allowedTypes(), true)) {
                //     abort(400, 'Invalid value for penalty_type_search');
                // }

                // allowed values are set in here
                if (in_array($penaltyTypeSearch, \App\Models\Penalty::allowedTypes(), true)) {     // 'true' - -   -   -   -   -   - => checks BOTH "VALUE" - & - "penalty_type" must match. (i.e. compared using '===')   -> # STRICT COMPARISON
                                                                                            // 'if false  - or - 'NOT set'  - => checks ONLY "VALUE" -  -   -   -   -   -   - (i.e. compared using '==')    -> # LOOSE COMPARISON
                    
                    // NO probability this could be NULL, BUT is added Just in case
                    if (is_null($penaltyTypeSearch)) {
                        $q->whereNull('penalty_type');
                    } else {
                        $q->where('penalty_type', $penaltyTypeSearch);
                    }
                }


            });
    }
}