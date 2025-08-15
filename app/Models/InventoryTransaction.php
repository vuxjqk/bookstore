<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'book_id',
        'purchase_order_id',
        'order_id',
        'transaction_type',
        'quantity',
        'transaction_date',
        'notes',
    ];
}
