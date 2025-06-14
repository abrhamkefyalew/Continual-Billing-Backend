<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetUnit extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'asset_units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_unit_code',
        'enterprise_id',
        'asset_main_id',
        'payer_id',
        'directive_id',
        'penalty_id',
        'penalty_starts_after_days',
        'service_termination_penalty',
        'price_principal',
        'is_payment_by_term_end',
        'payment_status',
        'start_date',
        'end_date',
        'original_end_date',
        'is_terminated',
        'payer_can_terminate',
        'is_engaged',
        'asset_unit_name',
        'asset_unit_description',
    ];
    


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'original_end_date' => 'date',
    ];




    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }

    public function assetMain()
    {
        return $this->belongsTo(AssetMain::class);
    }

    public function payer()
    {
        return $this->belongsTo(Payer::class);
    }

    public function directive()
    {
        return $this->belongsTo(Directive::class);
    }

    public function penalty()
    {
        return $this->belongsTo(Penalty::class);
    }



    public function invoiceUnits()
    {
        return $this->hasMany(InvoiceUnit::class);
    }





    // constants
    //
    // payment constants
    public const ASSET_UNIT_PAYMENT_NOT_STARTED = 'PAYMENT_NOT_STARTED';
    public const ASSET_UNIT_PAYMENT_STARTED = 'PAYMENT_STARTED';
    public const ASSET_UNIT_PAYMENT_LAST = 'PAYMENT_LAST';
    public const ASSET_UNIT_PAYMENT_COMPLETED = 'PAYMENT_COMPLETED';
    public const ASSET_UNIT_PAYMENT_TERMINATED = 'PAYMENT_TERMINATED';


    /**
     * central list of Allowed AssetUnit Payment Statuses as a method
     * 
     */
    public static function allowedTypes(): array
    {
        return [
            self::ASSET_UNIT_PAYMENT_NOT_STARTED,
            self::ASSET_UNIT_PAYMENT_STARTED,
            self::ASSET_UNIT_PAYMENT_LAST,
            self::ASSET_UNIT_PAYMENT_COMPLETED,
            self::ASSET_UNIT_PAYMENT_TERMINATED,
        ];
    }



    /**
     * Get validation rules using Rule::in with constants
     */
    public static function getRules(): array
    {
        //
        return [
            'payment_status' => [
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
            'payment_status.in' => 'INVALID Asset Unit Payment Status. Allowed values are: ' . implode(', ', self::allowedTypes()) . '.',  // this is just returning ERROR message,  SO using = [ implode(', ', self::allowedTypes()) ] here is NOT a Problem.
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
