<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_order_code',
        'order_date',
        'total_amount',
        'status',
        'notes',
    ];
}
