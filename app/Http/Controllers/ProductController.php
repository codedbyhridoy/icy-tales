<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, Category $category)
    {
        $categories = Cache::remember('categories', now()->addHours(6), function () {
            return Category::where('status', true)
                ->orderBy('name')
                ->get();
        });

        $products = Product::query()
            ->with(['category', 'options.values', 'variants', 'images'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->where('is_featured', false)
            ->frontendFilter(
                $request->only([
                    'search',
                    'category',
                    'min_price',
                    'max_price',
                    'sort',
                ])
            )
            ->paginate(6)
            ->withQueryString();

        $featured_products = Product::query()
            ->with([
                'category',
                'images',
                'options.values',
                'variants',
            ])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->paginate(10, ['*'], 'featured_page');

        return Inertia::render('Frontend/ProductCategoryPage', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $category->slug,
            'featured_products' => $featured_products,
            'filters' => $request->only([
                'search',
                'category',
                'min_price',
                'max_price',
                'sort'
            ])
        ]);
    }

    public function show(Product $product)
    {
        $product->load([
            'images',
            'options.values',
            'variants.optionValues.option',

            'reviews' => function ($query) {
                $query->where('status', 'approved')
                    ->with('user')
                    ->latest();
            },

        ]);

        $related_products = Product::query()
            ->with(['category', 'options.values', 'variants', 'images'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category->id)
            ->where('is_active', true)
            ->where('is_featured', false)
            ->paginate($request->per_page ?? 6)
            ->withQueryString();


        $canReview = false;

        if (Auth::check()) {
            $canReview = Auth::user()
                ->orders()
                ->where('status', 'completed')
                ->wherehas('orderItems.variant', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();
        }

        return Inertia::render('Frontend/ProductDetailsPage', [
            'product' => $product,
            'related_products' => $related_products,
            'canReview' => $canReview
        ]);
    }
}
