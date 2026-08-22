<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\FAcades\Cache;
use App\Models\Product;
use App\Models\Category;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home(Request $request) {
        $categories = Cache::remember('categories', now()->addHours(6), function () {
            return Category::where('status', true)
                ->latest()
                ->take(4)
                ->get();
        });

        $page = $request->input('featured-page', 1);

        $featured_products = Cache::remember(
            "featured_products_{$page}",
            now()->addMinutes(30),
            fn() => Product::query()
                ->with([
                    'category',
                    'images',
                    'options.values',
                    'variants',
                ])
                ->where('is_active', true)
                ->where('is_featured', true)
                ->latest()
                ->paginate(12, ['*'], 'featured_page')
        );

        return Inertia::render('Frontend/HomePage', [
            'categories' => $categories,
            'featured_products' => $featured_products
        ]);
    }
}
