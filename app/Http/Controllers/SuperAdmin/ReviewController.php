<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Review;
use Inertia\Inertia;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])->latest()
            ->paginate($request->per_page ?? 12);

        return Inertia::render('SuperAdmin/Store/Reviews/Index', [
            'reviews' => $reviews
        ]);
    }

    /**
     * Approve a review.
     */
    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved'
        ]);

        Redirect::back()->with('success', 'Review approved.');
    }

    /**
     * Reject a review.
     */
    public function reject(Review $review)
    {
        $review->update([
            'status' => 'rejected'
        ]);

        Redirect::back()->with('success', 'Review rejected.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review->load([
            'user',
            'product'
        ]);

        return Inertia::render('SuperAdmin/Store/Reviews/Show', [
            'review' => $review
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return Redirect::back()->with('success', 'Review deleted successfully');
    }
}
