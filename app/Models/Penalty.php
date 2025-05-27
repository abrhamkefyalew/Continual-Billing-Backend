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


    protected $table = 'directives';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
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
