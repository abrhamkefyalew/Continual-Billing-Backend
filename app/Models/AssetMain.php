<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Api\V1\NonQueuedMediaConversions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AssetMain extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, NonQueuedMediaConversions;


    protected $table = 'asset_mains';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enterprise_id',
        'asset_name',
        'asset_description',
        'is_active',
        'is_occupied',
        'type',
    ];




    // if the property have actual address
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }




    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }





    public function assetUnits()
    {
        return $this->hasMany(AssetUnit::class);
    }


    public function assetPools()
    {
        return $this->hasMany(AssetPool::class);
    }



    public function registerMediaConversions(?Media $media = null): void
    {
        $this->customizeMediaConversions();
    }


    // constants
    
    // media
    public const ASSET_MAIN_PROFILE_PICTURE = "ASSET_MAIN_PROFILE_PICTURE";


    // type of the assets
    public const ASSET_MAIN_OF_ASSET_UNIT_TYPE = 'ASSET_MAIN_OF_ASSET_UNIT_TYPE';
    public const ASSET_MAIN_OF_ASSET_POOL_TYPE = 'ASSET_MAIN_OF_ASSET_POOL_TYPE';
    

}
