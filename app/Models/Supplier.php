<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    protected const SORT_OPTIONS = [
        'a_to_z' => ['name', 'asc'],
        'z_to_a' => ['name', 'desc']
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? null,
            fn($q, $search) =>
            $q->where(
                fn($q) =>
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
            )
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
