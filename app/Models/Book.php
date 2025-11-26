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

    protected const SORT_OPTIONS = [
        'a_to_z' => ['title', 'asc'],
        'z_to_a' => ['title', 'desc'],
        'newest' => ['publication_year', 'desc'],
        'oldest' => ['publication_year', 'asc'],
        'price_low_to_high' => ['sale_price', 'asc'],
        'price_high_to_low' => ['sale_price', 'desc'],
        'highest_rated' => ['reviews_avg_rating', 'desc'],
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

    public function firstImage()
    {
        return $this->hasOne(BookImage::class)->orderBy('id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
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
        $query->when(
            $filters['search'] ?? null,
            fn($q, $search) =>
            $q->where(
                fn($q) =>
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
            )
        );

        $this->applyRelationFilter($query, 'categories', 'categories', $filters);
        $this->applyRelationFilter($query, 'publisher', 'publishers', $filters);

        $query->when(
            isset($filters['price_min']) && is_numeric($filters['price_min']),
            fn($q) =>
            $q->where('sale_price', '>=', $filters['price_min'])
        );
        $query->when(
            isset($filters['price_max']) && is_numeric($filters['price_max']),
            fn($q) =>
            $q->where('sale_price', '<=', $filters['price_max'])
        );

        $query->when(
            isset($filters['rating']) && is_numeric($filters['rating']),
            fn($q) =>
            $q->withAvg('reviews', 'rating')
                ->where('reviews_avg_rating', '>=', $filters['rating'])
        );

        $query->when(
            !empty($filters['languages']) && is_array($filters['languages']),
            fn($q) =>
            $q->whereIn('language', $filters['languages'])
        );
        $query->when(
            !empty($filters['statuses']) && is_array($filters['statuses']) && !empty($filters['statuses'][0]),
            fn($q) =>
            $q->whereIn('status', $filters['statuses'])
        );

        $query->when(
            $filters['category_slug'] ?? null,
            fn($q, $slug) =>
            $q->whereHas('categories', fn($qq) => $qq->where('slug', $slug))
        );

        $query->when(
            $filters['promotions'] ?? null,
            fn($q) =>
            $q->whereNotNull('sale_price')
                ->whereColumn('sale_price', '<', 'original_price')
        );

        $query->when($filters['sort'] ?? null, function ($q) use ($filters) {
            if (array_key_exists($filters['sort'], self::SORT_OPTIONS)) {
                [$column, $direction] = self::SORT_OPTIONS[$filters['sort']];
                if ($column === 'reviews_avg_rating') {
                    $q->withAvg('reviews', 'rating');
                }
                $q->orderBy($column, $direction);
            } else {
                $q->orderBy('created_at', 'desc');
            }
        }, fn($q) => $q->orderBy('created_at', 'desc'));

        return $query;
    }
}
