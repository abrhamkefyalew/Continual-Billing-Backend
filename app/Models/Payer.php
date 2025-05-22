<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payer extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\PayerFactory> */
    use HasFactory;
}
