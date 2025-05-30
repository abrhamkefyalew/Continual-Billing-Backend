<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
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
    public const ASSET_MAIN_OF_ASSET_UNIT_TYPE = 'ASSET_UNIT_TYPE';
    public const ASSET_MAIN_OF_ASSET_POOL_TYPE = 'ASSET_POOL_TYPE';




    /**
     * central list of Allowed AssetMain Types as a mutable property
     * 
     */
    public static $allowedTypes = [
        self::ASSET_MAIN_OF_ASSET_UNIT_TYPE,
        self::ASSET_MAIN_OF_ASSET_POOL_TYPE,
    ];




    //
    // DRY
    //
    //
    /**
     * Get validation rules using Rule::in with constants
     */
    public static function getRules(): array
    {
        //
        return [
            'type' => [
                'required', 'string', Rule::in(self::$allowedTypes),
            ],
        ];
    }


    /**
     * Custom validation messages
     */
    public static function getMessages(): array
    {
        return [
            'type.in' => 'INVALID Asset Main Type. Allowed values are: ' . implode(', ', self::$allowedTypes) . '.',  // this is just returning ERROR message,  SO using = [ implode(', ', self::$allowedTypes) ] here is NOT a Problem.
        ];
    }

    /**
     * Validate model data before doing WRITE Operation
     * 
     * The "booting" method of the model.
     *
     * Registers a saving event that validates model attributes before persisting.
     * This ensures that only a validated data is written to Database during  - > save() / fill()->save() / create() / update() / updateOrCreate() operations.
     * 
     * But still NOT work for insert() and upsert() - Because these are Query Builder-level operations, bypassing Eloquent models entirely 
     * 
     * 
     *  = > RECOMMENDED BECAUSE
     *                      //
     *                      - Triggers Validation and Writing to DB during - > i.e. save(), create(), update(), updateOrCreate(), fill() operations.
     *                      - But still NOT work for insert() and upsert() - Because these are Query Builder-level operations, bypassing Eloquent models entirely, - so no events (and no validation) are triggered.
     * 
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $validator = Validator::make($model->attributesToArray(), self::getRules(), self::getMessages());

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
        });
    }

    

}
