<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $summary = $this->cartService->summary();

        return Inertia::render('Frontend/Cart/Index', [
            'items' => $this->cartService->getItems(),
            'subtotal' => $summary['subtotal'],
            'shipping' => $summary['shipping'],
            'total' => $summary['total']
        ]);
    }

    public function store(AddToCartRequest $request)
    {
        $variant = ProductVariant::with([
            'product.images',
            'optionValues.option'
        ])->findOrFail($request->product_variant_id);

        $this->cartService->add(
            $variant,
            $request->quantity
        );

        return Redirect::back()->with('success', 'Product added to cart successfully.');
    }

    public function update(
        UpdateCartItemRequest $request,
        CartItem $cartItem
    ) {
        $this->cartService->updateQuantity(
            $cartItem,
            $request->quantity
        );

        return Redirect::back();
    }

    public function remove(CartItem $cartItem)
    {
        $this->cartService->remove($cartItem);

        return Redirect::back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cartService->clear();

        return Redirect::back();
    }
}
