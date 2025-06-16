<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetPool extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'asset_pools';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'payer_id',  // for now this one will NOT be needed, putting this will make one collective/group asset be rented/contracted by a single payer   // abram check    // I will use a separate contract table to put in a single payer info for that assetPool
        'enterprise_id',
        'asset_main_id',
        'directive_id',
        'penalty_id',
        'penalty_starts_after_days',
        'service_termination_penalty',
        'price_principal',
        'is_payment_by_term_end',
        // 'payment_status', // since it is a collective asset , there is NO single payment status to identify completion or starting of payment, so this will NEVER be needed
        'start_date',
        'end_date',
        'original_end_date',
        'is_terminated',
        'payer_can_terminate',
        'is_engaged',
        'asset_pool_name',
        'asset_pool_description',
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

    // for now this one will NOT be needed // abrham check
    // public function payer()
    // {
    //     return $this->belongsTo(Payer::class);
    // }

    public function directive()
    {
        return $this->belongsTo(Directive::class);
    }

    public function penalty()
    {
        return $this->belongsTo(Penalty::class);
    }



    public function invoicePools()
    {
        return $this->hasMany(InvoicePool::class);
    }




    // THE following are NOT needed for asset_pool
    //      Because Asset Pool is payed by multiple payers, so it does NOT have payment status as one,  
    //      i.e. it is a collection of multiple payers,
    //
    //  there should be a separate contracts table for that JOINs the PAYER - & - the AssetPool, 
    //      in that contracts table we could hold & Handle the Following payment status logic
    //
    // // constants
    // //
    // // payment constants
    // public const ASSET_POOL_PAYMENT_NOT_STARTED = 'PAYMENT_NOT_STARTED';
    // public const ASSET_POOL_PAYMENT_STARTED = 'PAYMENT_STARTED';
    // public const ASSET_POOL_PAYMENT_LAST = 'PAYMENT_LAST';
    // public const ASSET_POOL_PAYMENT_COMPLETED = 'PAYMENT_COMPLETED';
    // public const ASSET_POOL_PAYMENT_TERMINATED = 'PAYMENT_TERMINATED';


    // /**
    //  * central list of Allowed AssetPool Payment Statuses as a mutable property
    //  * 
    //  */
    // public static $allowedTypes = [
    //     self::ASSET_POOL_PAYMENT_NOT_STARTED,
    //     self::ASSET_POOL_PAYMENT_STARTED,
    //     self::ASSET_POOL_PAYMENT_LAST,
    //     self::ASSET_POOL_PAYMENT_COMPLETED,
    //     self::ASSET_POOL_PAYMENT_TERMINATED,
    // ];


    // /**
    //  * Get validation rules using Rule::in with constants
    //  */
    // public static function getRules(): array
    // {
    //     //
    //     return [
    //         'payment_status' => [
    //             'required', 'string', Rule::in(self::$allowedTypes),
    //         ],
    //     ];
    // }


    // /**
    //  * Custom validation messages
    //  */
    // public static function getMessages(): array
    // {
    //     return [
    //         'payment_status.in' => 'INVALID Asset Pool Payment Status. Allowed values are: ' . implode(', ', self::$allowedTypes) . '.',
    //     ];
    // }



    // /**
    //  * Validate model data before doing WRITE Operation
    //  * 
    //  * The "booting" method of the model.
    //  *
    //  * Registers a saving event that validates model attributes before persisting.
    //  * This ensures that only a validated data is written to Database during  - > save() / fill()->save() / create() / update() / updateOrCreate() operations.
    //  * 
    //  * But still NOT work for insert() and upsert() - Because these are Query Builder-level operations, bypassing Eloquent models entirely 
    //  * 
    //  * 
    //  *  = > RECOMMENDED BECAUSE
    //  *                      //
    //  *                      - Triggers Validation and Writing to DB during - > i.e. save(), create(), update(), updateOrCreate(), fill() operations.
    //  *                      - But still NOT work for insert() and upsert() - Because these are Query Builder-level operations, bypassing Eloquent models entirely, - so no events (and no validation) are triggered.
    //  * 
    //  *
    //  * @return void
    //  */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($model) {
    //         $validator = Validator::make($model->attributesToArray(), self::getRules(), self::getMessages());

    //         if ($validator->fails()) {
    //             throw new \Exception($validator->errors()->first());
    //         }
    //     });
    // }
    


}
