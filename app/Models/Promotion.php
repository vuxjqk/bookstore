<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'discount_percentage',
        'max_discount_amount',
        'min_order_amount',
        'max_usage_count',
        'is_active',
        'start_date',
        'end_date',
    ];
}
