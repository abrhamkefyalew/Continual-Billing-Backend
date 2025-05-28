<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'enterprise_id',
        'asset_main_id',
        'payer_id',
        'directive_id',
        'penalty_id',
        'price_principal',
        'payment_status',
        'start_date',
        'end_date',
        'original_end_date',
        'is_terminated',
        'payer_can_terminate',
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



}
