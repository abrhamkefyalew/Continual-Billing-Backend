<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penalty extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\PenaltyFactory> */
    use HasFactory;
}
