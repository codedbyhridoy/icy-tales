<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name', 
        'slug',
        'image',
        'status'
    ];

    public function scopeFilter(Builder $query, array $filters) {
        $query ->when($filters['search'] ?? null, function (Builder $query, $search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
            });
        })
        ->when($filters['sort_by'] ?? null, function (Builder $query, $sortBy) use ($filters) {
            $sortType = $filters['sort_type'] ?? 'desc';

            $query->orderBy($sortBy, $sortType);
        });
    }
    
    public function products() {
        return $this->hasMany(Product::class);
    }
}
