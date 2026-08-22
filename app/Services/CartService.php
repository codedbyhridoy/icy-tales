<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getCart(): Cart
    {
        return Cart::firstOrCreate([
            'session_id' => session()->getId(),
        ]);
    }

    public function getItems()
    {
        return $this->getCart()
            ->items()
            ->with([
                'product.images',
                'variant.optionValues.option',
            ])
            ->latest()
            ->get();
    }

    public function count(): int
    {
        return $this->getCart()
            ->items()
            ->sum('quantity');
    }

    public function subtotal(): float
    {
        return $this->getCart()
            ->items()
            ->get()
            ->sum(fn($item) => $item->price_snapshot * $item->quantity);
    }

    public function getShipping(): int
    {
        $subtotal = $this->subtotal();

        if ($subtotal > 100) {
            return 0.00;
        }

        return 1.00;
    }

    public function summary(): array
    {
        $subtotal = $this->subtotal();
        $shipping = $this->getShipping();

        return [
            'count' => $this->count(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => 0,
            'total' => $subtotal + $shipping
        ];
    }

    public function items()
    {
        return $this->getCart()
            ->items()
            ->with([
                'product',
                'variant.optionValues.option',
            ])
            ->get();
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function add(
        ProductVariant $variant,
        int $quantity = 1
    ): CartItem {

        if ($variant->stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock.',
            ]);
        }

        $cart = $this->getCart();

        $item = $cart
            ->items()
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($item) {

            $newQuantity = $item->quantity + $quantity;

            if ($newQuantity > $variant->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Insufficient stock.',
                ]);
            }

            $item->update([
                'quantity' => $newQuantity,
            ]);

            return $item;
        }

        $options = $variant->getOptionsArray();

        $image = optional(
            $variant->product->images->first()
        )->image_path;

        return $cart->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'name_snapshot' => $variant->product->name,
            'sku_snapshot' => $variant->sku,
            'image_snapshot' => $image,
            'options_snapshot' => $options,
            'price_snapshot' => $variant->price,
            'quantity' => $quantity,
        ]);
    }


    public function updateQuantity(
        CartItem $item,
        int $quantity
    ): CartItem {

        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Quantity must be at least 1.',
            ]);
        }

        if (
            $item->variant &&
            $quantity > $item->variant->stock
        ) {
            throw ValidationException::withMessages([
                'quantity' => 'Insufficient stock.',
            ]);
        }

        $item->update([
            'quantity' => $quantity,
        ]);

        return $item;
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(): void
    {
        $this->getCart()
            ->items()
            ->delete();
    }
}
