<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Directive extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\DirectiveFactory> */
    use HasFactory;
}
