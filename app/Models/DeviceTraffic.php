<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceTraffic extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'device_traffic';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_id_description',
        'device_type',
        'user_agent',
        'ip',
        'ip_got_using_custom_function',
        'ip_behind_proxy_or_broadcast',
        'ip_advanced_deep_tracing',
        'url',
    ];



}
