<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author_id',
        'publisher_id',
        'isbn',
        'language',
        'description',
        'pages',
        'dimensions',
        'weight',
        'publication_year',
        'cover_type',
        'original_price',
        'sale_price',
        'stock_quantity',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(BookImage::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? null,
            function ($q, $search) {
                $q->where(
                    function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    }
                );
            }
        );

        $query->when(
            $filters['category'] ?? null,
            fn($q, $value) =>
            $q->whereHas(
                'categories',
                fn($q) =>
                $q->where('name', $value)
            )
        );

        $query->when(
            $filters['status'] ?? null,
            fn($q, $value) =>
            $q->where('status', $value)
        );

        return $query;
    }
}
