<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Cart;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',

        'product_variant_id',
        'name_snapshot',
        'sku_snapshot',
        'image_snapshot',
        'price_snapshot',
        'color_snapshot',
        'size_snapshot',

        'quantity'
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'options_snapshot' => 'array'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getLineTotalAttribute(): float
    {
        return $this->price_snapshot = $this->quantity;
    }

    public function getOptionAttribute($type) {
        $this->options_snapshot[$type] ?? null;
    }
}
