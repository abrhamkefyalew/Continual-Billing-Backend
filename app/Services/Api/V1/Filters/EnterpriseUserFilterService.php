<?php

namespace App\Services\Api\V1\Filters;

use Illuminate\Database\Eloquent\Builder;

class EnterpriseUserFilterService
{

    /**
     * Applies filters to the EnterpriseUser query based on request input.
     *
     * @param  Builder  $builder
     * @param  array    $filters
     * @return Builder
     */
    public static function applyEnterpriseUserFilter(Builder $builder, array $filters): Builder
    {
        return $builder
            ->when(isset($filters['enterprise_id_search']) && filled($filters['enterprise_id_search']), fn($q) =>
                $q->where('enterprise_id', $filters['enterprise_id_search']))
            ->when(isset($filters['first_name_search']) && filled($filters['first_name_search']), fn($q) =>
                $q->where('first_name', 'like', '%' . $filters['first_name_search'] . '%'))
            ->when(isset($filters['last_name_search']) && filled($filters['last_name_search']), fn($q) =>
                $q->where('last_name', 'like', '%' . $filters['last_name_search'] . '%'))
            ->when(isset($filters['email_search']) && filled($filters['email_search']), fn($q) =>
                $q->where('email', 'like', '%' . $filters['email_search'] . '%'))
            ->when(isset($filters['phone_number_search']) && filled($filters['phone_number_search']), fn($q) =>
                $q->where('phone_number', 'like', '%' . $filters['phone_number_search'] . '%'))
            ->when(isset($filters['is_active_search']) && filled($filters['is_active_search']), fn($q) =>
                $q->where('is_active', $filters['is_active_search']))
            ->when(isset($filters['is_admin_search']) && filled($filters['is_admin_search']), fn($q) =>
                $q->where('is_admin', $filters['is_admin_search']));
    }
}