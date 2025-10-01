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
        return $query
            ->when(
                $filters['search'] ?? null,
                fn($q, $search) =>
                $q->where(
                    fn($q) =>
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                )
            )
            ->when(
                $filters['sort'] ?? null,
                fn($q, $sort) =>
                array_key_exists($sort, self::SORT_OPTIONS)
                    ? $q->orderBy(...self::SORT_OPTIONS[$sort])
                    : $q->orderBy('created_at', 'desc')
            );
    }
}
