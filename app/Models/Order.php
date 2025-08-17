<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'order_date',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    protected const SORT_OPTIONS = [
        'a_to_z' => ['customer_name', 'asc'],
        'z_to_a' => ['customer_name', 'desc'],
        'newest' => ['order_date', 'desc'],
        'oldest' => ['order_date', 'asc'],
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->orderBy('id');;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? null,
            fn($q, $search) =>
            $q->where(
                fn($q) =>
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
            )
        );

        $query->when(
            $filters['order_date'] ?? null,
            fn($q) =>
            $q->whereDate('order_date', '>=', $filters['order_date'])
        );

        $query->when(
            $filters['status'] ?? null,
            fn($q) =>
            $q->where('status', $filters['status'])
        );

        $query->when($filters['sort'] ?? null, function ($q) use ($filters) {
            if (array_key_exists($filters['sort'], self::SORT_OPTIONS)) {
                [$column, $direction] = self::SORT_OPTIONS[$filters['sort']];
                $q->orderBy($column, $direction);
            } else {
                $q->orderBy('created_at', 'desc');
            }
        }, fn($q) => $q->orderBy('created_at', 'desc'));

        return $query;
    }
}
