<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePool extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\InvoicePoolFactory> */
    use HasFactory;
}
