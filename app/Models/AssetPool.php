<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetPool extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\AssetPoolFactory> */
    use HasFactory;




    

    // constants
    //
    // payment constants
    public const ASSET_POOL_PAYMENT_NOT_STARTED = 'PAYMENT_NOT_STARTED';
    public const ASSET_POOL_PAYMENT_STARTED = 'PAYMENT_STARTED';
    public const ASSET_POOL_PAYMENT_LAST = 'PAYMENT_LAST';
    public const ASSET_POOL_PAYMENT_COMPLETED = 'PAYMENT_COMPLETED';
    public const ASSET_POOL_PAYMENT_TERMINATED = 'PAYMENT_TERMINATED';


}
