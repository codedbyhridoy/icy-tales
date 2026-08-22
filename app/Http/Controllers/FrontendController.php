<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\State;
use App\Models\City;
use Inertia\Inertia;

class FrontendController extends Controller
{
    public function index() {
        $products = Product::query()
            ->with('images')
            ->latest()
            ->paginate(12);

        return Inertia::render('Frontend/HomePage', [
            'products' => $products
        ]);
    }

    public function states() {
        $states = State::where('status', 1)->with('cities')->get();

        return response()->json(['data']);
    }

    public function cities() {
        return "This";
    }
}
