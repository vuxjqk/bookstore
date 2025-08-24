<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'purchase_order_item_id',
        'order_item_id',
        'transaction_type',
        'quantity',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    protected const SORT_OPTIONS = [
        'a_to_z' => ['books.title', 'asc'],
        'z_to_a' => ['books.title', 'desc'],
        'newest' => ['transaction_date', 'desc'],
        'oldest' => ['transaction_date', 'asc'],
    ];

    public function purchase_order_item()
    {
        return $this->belongsTo(PurchaseOrderitem::class);
    }

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['transaction_type'] ?? null,
            fn($q) =>
            $q->where('transaction_type', $filters['transaction_type'])
        );

        $query->when(
            $filters['transaction_date'] ?? null,
            fn($q) =>
            $q->whereDate('transaction_date', '>=', $filters['transaction_date'])
        );

        $query->when(
            $filters['search'] ?? null,
            fn($q, $search) =>
            $q->where('notes', 'like', "%{$search}%")
        );

        $query->when($filters['sort'] ?? null, function ($q) use ($filters) {
            if (array_key_exists($filters['sort'], self::SORT_OPTIONS)) {
                [$column, $direction] = self::SORT_OPTIONS[$filters['sort']];
                if ($column === 'books.title') {
                    $q->leftJoin('purchase_order_items', 'inventory_transactions.purchase_order_item_id', '=', 'purchase_order_items.id');
                    $q->leftJoin('order_items', 'inventory_transactions.order_item_id', '=', 'order_items.id');

                    $q->leftJoin('books', function ($join) {
                        $join->on('purchase_order_items.book_id', '=', 'books.id')
                            ->orOn('order_items.book_id', '=', 'books.id');
                    });

                    $q->orderBy('books.title', $direction);
                    $q->select('inventory_transactions.*');
                }
                $q->orderBy($column, $direction);
            } else {
                $q->orderBy('created_at', 'desc');
            }
        }, fn($q) => $q->orderBy('created_at', 'desc'));

        return $query;
    }
}
