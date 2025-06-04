<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    // penalty type constants (i.e. for the column penalty_type)
    public const PENALTY_TYPE_FLAT = 'PENALTY_TYPE_FLAT';       // penalty is calculated once for each UNPAID Term               // penalty amount is calculated from the principal price using percent
    public const PENALTY_TYPE_DAILY = 'PENALTY_TYPE_DAILY';     // penalty is calculated for each day inside the UNPAID Term     // penalty amount is calculated from the daily amount of the principal price using percent




    /**
     * central list of Allowed Penalty Payment Statuses as a method
     * 
     */
    public static function allowedTypes(): array
    {
        return [
            self::PENALTY_TYPE_FLAT,
            self::PENALTY_TYPE_DAILY,
        ];
    }



    /**
     * Get validation rules using Rule::in with constants
     */
    public static function getRules(): array
    {
        //
        return [
            'penalty_type' => [
                'required', 'string', Rule::in(self::allowedTypes()),
            ],
        ];
    }


    /**
     * Custom validation messages
     */
    public static function getMessages(): array
    {
        return [
            'penalty_type.in' => 'INVALID Penalty Type (penalty_type). Allowed values are: ' . implode(', ', self::allowedTypes()) . '.',  // this is just returning ERROR message,  SO using = [ implode(', ', self::allowedTypes()) ] here is NOT a Problem.
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
