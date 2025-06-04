<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoice_units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_code',
        'asset_unit_id',
        'transaction_id_system',
        'transaction_id_banks',
        'start_date',
        'end_date',
        'price',
        'penalty',
        'immune_to_penalty',
        'status',
        'paid_date',
        'payment_method',
        'reason',
        'reason_description',
        'request_payload',
        'response_payload',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'paid_date' => 'date',
        'request_payload' => 'array', // USED
        'response_payload' => 'array', // USED
        // 'response_payload' => 'json', // this works also
    ];



    public function getPriceTotalAttribute()
    {
        $priceTotal = $this->price;

        if (is_numeric($priceTotal) && is_numeric($this->penalty)) {
            $priceTotal += $this->penalty;
        }

        return $priceTotal;
    }



    public function assetUnit()
    {
        return $this->belongsTo(AssetUnit::class);
    }





    // constants
    //
    //
    // InvoiceUnit payment status constants (i.e. for the column status)
    public const INVOICE_STATUS_PAID = 'PAID'; // paid invoice
    public const INVOICE_STATUS_NOT_PAID = 'NOT_PAID'; // not paid invoice


    // Invoice Payment Method constants   // payment_method constants
    public const INVOICE_TELE_BIRR = 'TELE_BIRR';
    public const INVOICE_CBE_MOBILE_BANKING = 'CBE_MOBILE_BANKING';
    public const INVOICE_CBE_BIRR = 'CBE_BIRR';
    public const INVOICE_BOA = 'BOA';
    public const SANTIM_PAY_WALLET = 'SANTIM_PAY_WALLET';






    /**
     * central list of Allowed InvoiceUnit Payment Statuses (status) as a method
     * 
     */
    public static function allowedTypes(): array
    {
        return [
            self::INVOICE_STATUS_PAID,
            self::INVOICE_STATUS_NOT_PAID,
        ];
    }



    /**
     * Get validation rules using Rule::in with constants
     */
    public static function getRules(): array
    {
        //
        return [
            'status' => [
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
            'status.in' => 'INVALID Invoice Unit Payment Status (status). Allowed values are: ' . implode(', ', self::allowedTypes()) . '.',  // this is just returning ERROR message,  SO using = [ implode(', ', self::allowedTypes()) ] here is NOT a Problem.
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
