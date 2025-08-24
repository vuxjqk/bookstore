<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'order_date',
        'total_amount',
        'discount_amount',
        'status',
        'notes',
        'employee_id',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    protected const SORT_OPTIONS = [
        'a_to_z' => ['suppliers.name', 'asc'],
        'z_to_a' => ['suppliers.name', 'desc'],
        'newest' => ['order_date', 'desc'],
        'oldest' => ['order_date', 'asc'],
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    protected function applyRelationFilter($query, $relation, $filterKey, $filters)
    {
        $query->when(
            !empty($filters[$filterKey]) && is_array($filters[$filterKey]) && !empty($filters[$filterKey][0]),
            fn($q) =>
            $q->whereHas(
                $relation,
                fn($q) =>
                $q->whereIn('id', $filters[$filterKey])
            )
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $this->applyRelationFilter($query, 'supplier', 'suppliers', $filters);

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
                if ($column === 'suppliers.name') {
                    $q->leftJoin('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id');
                    $q->orderBy('suppliers.name', $direction);
                    $q->select('purchase_orders.*');
                }
                $q->orderBy($column, $direction);
            } else {
                $q->orderBy('created_at', 'desc');
            }
        }, fn($q) => $q->orderBy('created_at', 'desc'));

        return $query;
    }
}
