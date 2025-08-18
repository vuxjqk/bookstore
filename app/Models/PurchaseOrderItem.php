<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'book_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
