<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $fillable = [
        'product_option_id',
        'value',
        'hex_code',
        'sort_order',
    ];

    public function option()
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_option_value'
        );
    }
}