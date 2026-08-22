<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Product $product)
    {
        $user = Auth::user();

        // $hasPurchased = OrderItem::where('product_id', $product->id)
        //     ->whereHas('order', function ($query) use ($user) {
        //         $query->where('user_id', $user->id)
        //             ->where('status', 'completed');
        //     })
        //     ->exists();

        // if (! $hasPurchased) {
        //     return back()->with(
        //         'error',
        //         'You can only review products you have purchased.'
        //     );
        // }

        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with(
                'error',
                'You have already reviewed this product.'
            );
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Thank you! Your review has been submitted and is awaiting approval.'
        );
    }
}
