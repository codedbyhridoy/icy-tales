<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Member extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'designation',
        'bio',
        'image',
        'facebook',
        'instagram',
        'youtube',
        'sort_order',
        'is_active'
    ];

    protected $appends = [
        'socials',
    ];

    public function scopeFilter(Builder $query, array $filters) {
        $query->when($filters['search'] ?? null, function (Builder $query, $search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}")
                ->orWhere('designation', 'like', "%{$search}");
            });
        })
        ->when($filters['is_active'] ?? null, function (Builder $query, $isActive) {
            $query->where('is_active', $isActive);
        })
        ->when($filters['sort_by'] ?? null, function (Builder $query, $sortBy) {
            $sortType = $filters['sort_type'] ?? 'desc';
            $query->orderBy($sortBy, $sortType);
        })
        ->when($filters['designation'] ?? null, function (Builder $query, $designation) {
            $query->where('designation', $designation);
        });
    }

    public function getSocialsAttribute(): array
    {
        return array_filter([
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'youtube' => $this->youtube,
        ]);
    }
}
