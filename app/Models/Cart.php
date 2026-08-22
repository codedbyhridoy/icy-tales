<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Cart extends Model
{
    protected $fillable = ['session_id'];

    public function items() {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->line_total;
        });
    }

    public function getItemsCountAttribute(): int 
    {
        return $this->items->sum('quantity');
    }
}
