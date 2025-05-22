<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetUnit extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\AssetUnitFactory> */
    use HasFactory;
}
