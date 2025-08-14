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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function scopeFilter($query, array $filters)
    {
        // Search by title or slug
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

        // Filter by multiple categories (checkboxes)
        $query->when(
            !empty($filters['categories']) && is_array($filters['categories']),
            function ($q) use ($filters) {
                $q->whereHas('categories', function ($q) use ($filters) {
                    $q->whereIn('id', $filters['categories']);
                });
            }
        );

        // Filter by price range
        $query->when(
            isset($filters['price_min']) && is_numeric($filters['price_min']),
            fn($q, $value) => $q->where('sale_price', '>=', $filters['price_min'])
        );

        $query->when(
            isset($filters['price_max']) && is_numeric($filters['price_max']),
            fn($q, $value) => $q->where('sale_price', '<=', $filters['price_max'])
        );

        // Filter by rating
        $query->when(
            isset($filters['rating']) && is_numeric($filters['rating']),
            function ($q) use ($filters) {
                $q->whereHas('reviews', function ($q) use ($filters) {
                    $q->select('book_id')
                        ->groupBy('book_id')
                        ->havingRaw('AVG(rating) >= ?', [$filters['rating']]);
                });
            }
        );

        // Filter by multiple publishers (checkboxes)
        $query->when(
            !empty($filters['publishers']) && is_array($filters['publishers']),
            function ($q) use ($filters) {
                $q->whereHas('publisher', function ($q) use ($filters) {
                    $q->whereIn('id', $filters['publishers']);
                });
            }
        );

        // Filter by language
        $query->when(
            !empty($filters['languages']) && is_array($filters['languages']),
            fn($q) => $q->whereIn('language', $filters['languages'])
        );

        // Filter by status
        $query->when(
            $filters['status'] ?? null,
            fn($q, $value) => $q->where('status', $value)
        );

        // Filter by stock availability (optional enhancement)
        $query->when(
            !empty($filters['in_stock']) && is_array($filters['in_stock']),
            function ($q) use ($filters) {
                $q->where(function ($query) use ($filters) {
                    $values = array_unique($filters['in_stock']);
                    if (count($values) === 1) {
                        $query->where('stock_quantity', $values[0] == '1' ? '>' : '=', 0);
                    } else {
                        $query->where('stock_quantity', '>=', 0);
                    }
                });
            }
        );

        // Sorting
        $query->when(
            $filters['sort'] ?? null,
            function ($q) use ($filters) {
                switch ($filters['sort']) {
                    case 'newest':
                        $q->orderBy('publication_year', 'desc');
                        break;
                    case 'oldest':
                        $q->orderBy('publication_year', 'asc');
                        break;
                    case 'price_low_to_high':
                        $q->orderBy('sale_price', 'asc');
                        break;
                    case 'price_high_to_low':
                        $q->orderBy('sale_price', 'desc');
                        break;
                    case 'highest_rated':
                        $q->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                        break;
                    default:
                        $q->orderBy('created_at', 'desc');
                        break;
                }
            },
            fn($q) => $q->orderBy('created_at', 'desc') // Default sort
        );

        return $query;
    }
}
