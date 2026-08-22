<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
    ];

    public function getOptionsArray(): array
    {
        return $this->optionValues->mapWithKeys(function ($optionValue) {
            if (!$optionValue->option) {
                return [];
            }

            return [
                strtolower($optionValue->option->type) => $optionValue->value
            ];
        })->filter() // 
            ->toArray();
    }

     public function optionValues()
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'product_variant_option_values', // table name
            'product_variant_id',
            'product_option_value_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

      public function getOptionTextAttribute()
    {
        return $this->optionValues
            ->pluck('value')
            ->implode(' / ');
    }

    public function isInStock($quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }
}
