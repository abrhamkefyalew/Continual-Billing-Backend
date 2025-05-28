<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penalty extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\PenaltyFactory> */
    use HasFactory;


    protected $table = 'penalties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'penalty_starts_after_days',
        'percent_of_principal_flat',
        'percent_of_principal_daily_rate',
        'service_termination_penalty',
        'is_active',
    ];



    public function assetUnits()
    {
        $this->hasMany(AssetUnit::class);
    }


    public function assetPools()
    {
        $this->hasMany(AssetPool::class);
    }

    
}
