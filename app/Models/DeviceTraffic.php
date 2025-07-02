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
        'device_type',
        'user_agent',
        'ip',
        'ip_got_using_custom_function',
        'url',
    ];



}
