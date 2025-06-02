<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;

class EnterpriseFilterService
{

    /**
     * Applies filters to the AssetMain query based on request input.
     *
     * @param  Builder  $builder
     * @param  array    $filters
     * @return Builder
     */
    public static function applyEnterpriseFilter(Builder $builder, array $filters): Builder
    {
        return $builder
            ->when(isset($filters['is_active_search']) && filled($filters['is_active_search']), fn($q) =>
                $q->where('is_active', $filters['is_active_search']))
            ->when(isset($filters['is_approved_search']) && filled($filters['is_approved_search']), fn($q) =>
                $q->where('is_approved', $filters['is_approved_search']))
            ->when(isset($filters['name_search']) && filled($filters['name_search']), fn($q) =>
                $q->where('name', 'like', '%' . $filters['name_search'] . '%'))
            ->when(isset($filters['enterprise_description_search']) && filled($filters['enterprise_description_search']), fn($q) =>
                $q->where('enterprise_description', 'like', '%' . $filters['enterprise_description_search'] . '%'))
            ->when(isset($filters['email_search']) && filled($filters['email_search']), fn($q) =>
                $q->where('email', 'like', '%' . $filters['email_search'] . '%'))
            ->when(isset($filters['phone_number_search']) && filled($filters['phone_number_search']), fn($q) =>
                $q->where('phone_number', 'like', '%' . $filters['phone_number_search'] . '%'));
    }
}