<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetUnit extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\AssetUnitFactory> */
    use HasFactory;






    // constants
    //
    // payment constants
    public const ASSET_UNIT_PAYMENT_NOT_STARTED = 'PAYMENT_NOT_STARTED';
    public const ASSET_UNIT_PAYMENT_STARTED = 'PAYMENT_STARTED';
    public const ASSET_UNIT_PAYMENT_LAST = 'PAYMENT_LAST';
    public const ASSET_UNIT_PAYMENT_COMPLETED = 'PAYMENT_COMPLETED';
    public const ASSET_UNIT_PAYMENT_TERMINATED = 'PAYMENT_TERMINATED';



}
