<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePool extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoice_pools';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_code',
        'asset_pool_id',
        'payer_id',
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



    public function assetPools()
    {
        return $this->belongsTo(AssetPool::class);
    }


    public function payer()
    {
        return $this->belongsTo(Payer::class);
    }




    // Invoice status constants
    public const INVOICE_STATUS_PAID = 'PAID'; // paid invoice
    public const INVOICE_STATUS_NOT_PAID = 'NOT_PAID'; // not paid invoice


    // Invoice Payment Method constants   // payment_method constants
    public const INVOICE_TELE_BIRR = 'TELE_BIRR';
    public const INVOICE_CBE_MOBILE_BANKING = 'CBE_MOBILE_BANKING';
    public const INVOICE_CBE_BIRR = 'CBE_BIRR';
    public const INVOICE_BOA = 'BOA';
    public const SANTIM_PAY_WALLET = 'SANTIM_PAY_WALLET';

    
}
