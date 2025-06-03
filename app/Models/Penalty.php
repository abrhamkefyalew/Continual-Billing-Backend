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
        'penalty_type',
        'percent_of_principal_price',
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



    // constants
    //
    // penalty type constants
    public const PENALTY_TYPE_FLAT = 'PENALTY_TYPE_FLAT';       // penalty is calculated once for each UNPAID Term               // penalty amount is calculated from the principal price using percent
    public const PENALTY_TYPE_DAILY = 'PENALTY_TYPE_DAILY';     // penalty is calculated for each day inside the UNPAID Term     // penalty amount is calculated from the daily amount of the principal price using percent

    
}
