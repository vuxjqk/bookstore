<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'shipping_address',
        'order_date',
        'total_amount',
        'status',
    ];
}
