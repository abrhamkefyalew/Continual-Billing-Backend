<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;

class AssetMainFilterService
{

    /**
     * Applies filters to the AssetMain query based on request input.
     *
     * @param  Builder  $builder
     * @param  array    $filters
     * @return Builder
     */
    public static function applyAssetMainFilter(Builder $builder, array $filters): Builder
    {
        return $builder
            ->when(isset($filters['enterprise_id_search']) && filled($filters['enterprise_id_search']), fn($q) =>
                $q->where('enterprise_id', $filters['enterprise_id_search']))
            ->when(isset($filters['is_active_search']) && filled($filters['is_active_search']), fn($q) =>
                $q->where('is_active', $filters['is_active_search']))
            ->when(isset($filters['asset_name_search']) && filled($filters['asset_name_search']), fn($q) =>
                $q->where('asset_name', 'like', '%' . $filters['asset_name_search'] . '%'))
            ->when(isset($filters['asset_description_search']) && filled($filters['asset_description_search']), fn($q) =>
                $q->where('asset_description', 'like', '%' . $filters['asset_description_search'] . '%'))
            ->when(isset($filters['type_search']) && filled($filters['type_search']), function ($q) use ($filters) {
                $typeSearch = $filters['type_search'];

                $allowedAssetMainTypes = [
                    \App\Models\AssetMain::ASSET_MAIN_OF_ASSET_UNIT_TYPE,
                    \App\Models\AssetMain::ASSET_MAIN_OF_ASSET_POOL_TYPE,
                ];



                // OPTIONAL // since is it checked below also // may be redundant // use this if you want to return error for wrong values sent during filter
                // if (!in_array($typeSearch, $allowedAssetMainTypes, true)) {
                //     abort(400, 'Invalid value for type_search');
                // }

                // allowed values are set in here
                if (in_array($typeSearch, $allowedAssetMainTypes, true)) {    // 'true' - -   -   -   -   -   - => checks BOTH "VALUE" - & - "TYPE" must match. (i.e. compared using '===')   -> # STRICT COMPARISON
                                                                              // 'if false  - or - 'NOT set'  - => checks ONLY "VALUE" -  -   -   -   -   -   - (i.e. compared using '==')    -> # LOOSE COMPARISON
                    
                    // NO probability this could be NULL, BUT is added Just in case
                    if (is_null($typeSearch)) {
                        $q->whereNull('type');
                    } else {
                        $q->where('type', $typeSearch);
                    }
                }



                /*

                if (in_array($typeSearch, \App\Models\AssetMain::$allowedTypes, true)) {    // 'true' - -   -   -   -   -   - => checks BOTH "VALUE" - & - "TYPE" must match. (i.e. compared using '===')   -> # STRICT COMPARISON
                                                                                            // 'if false  - or - 'NOT set'  - => checks ONLY "VALUE" -  -   -   -   -   -   - (i.e. compared using '==')    -> # LOOSE COMPARISON
                    
                    // NO probability this could be NULL, BUT is added Just in case
                    if (is_null($typeSearch)) {
                        $q->whereNull('type');
                    } else {
                        $q->where('type', $typeSearch);
                    }
                }

                */


            });
    }
}