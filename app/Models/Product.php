<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Review;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'category_id',
        'price',
        'is_featured',
        'is_active',
    ];

    protected $appends = [
        'min_price',
        'max_price',
        'total_stock',
        'average_rating',
        'rating_counts',
        'total_ratings',
        'reviews_count'
    ];


    public function scopeFrontendFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when( $filters['search'] ?? null,  fn($query, $search) =>
                 $query->where('name', 'like', "%{$search}%")
            )

            ->when(
                $filters['category'] ?? null,
                fn($query, $category) =>
                $query->where('category_id', $category)
            )

            ->when(
                isset($filters['min_price']) && is_numeric($filters['min_price']),
                fn($query) =>
                $query->whereHas('variants', function ($q) use ($filters) {
                    $q->where('price', '>=', $filters['min_price']);
                })
            )

            ->when(
                isset($filters['max_price']) && is_numeric($filters['max_price']),
                fn($query) =>
                $query->whereHas('variants', function ($q) use ($filters) {
                    $q->where('price', '<=', $filters['max_price']);
                })
            )

            ->when(
                $filters['sort'] ?? null,
                function ($query, $sort) {

                    match ($sort) {

                        'latest'
                        => $query->latest(),

                        'popular'
                        => $query
                            ->withCount('orderItems')
                            ->orderByDesc('order_items_count'),

                        'price_low'
                        => $query
                            ->withMin('variants', 'price')
                            ->orderBy('variants_min_price'),

                        'price_high'
                        => $query
                            ->withMin('variants', 'price')
                            ->orderByDesc('variants_min_price'),

                        default
                        => $query->latest(),
                    };
                },
                fn($query) => $query->latest()
            );
    }

    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price');
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants()->max('price');
    }

    public function getTotalStockAttribute()
    {
        return $this->variants()->sum('stock');
    }

    public function getAverageRatingAttribute()
    {
        if ($this->relationLoaded('reviews')) {
            return round($this->reviews->avg('rating') ?? 0, 1);
        }

        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getTotalRatingsAttribute() {
        return $this->reviews()
            ->whereNotNull('rating')
            ->count();
    }

    public function getRatingCountsAttribute()
    {
        return [
            5 => $this->reviews->where('rating', 5)->count(),
            4 => $this->reviews->where('rating', 4)->count(),
            3 => $this->reviews->where('rating', 3)->count(),
            2 => $this->reviews->where('rating', 2)->count(),
            1 => $this->reviews->where('rating', 1)->count(),
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
