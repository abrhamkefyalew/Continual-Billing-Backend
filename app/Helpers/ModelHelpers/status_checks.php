<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('abort_if_inactive')) { 
    
    /**
     * Abort if the given model is null or not active.
     *
     * @param  Model|null  $model
     * @param  string      $type
     * @param  int|string  $id
     * @return void
     */
    function abort_if_inactive(?Model $model, string $type, int|string $id): void
    {
        if (! $model || $model?->is_active !== 1) {
            abort(422, "The {$type} with ID {$id} is NOT active.");
        }
    }
}




if (!function_exists('abort_if_unapproved')) {

    /**
     * Abort if the given model is null or not approved.
     *
     * @param  Model|null  $model
     * @param  string      $type
     * @param  int|string  $id
     * @return void
     */
    function abort_if_unapproved(?Model $model, string $type, int|string $id): void
    {
        if (! $model || $model?->is_approved !== 1) {
            abort(422, "The {$type} with ID {$id} is NOT approved.");
        }
    }
}
