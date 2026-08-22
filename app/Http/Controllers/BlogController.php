<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('status', 'published')
            ->with([
                'author',
                'category',
                'tags'
            ])
            ->paginate(9);

        $categories = PostCategory::query()
            ->where('status', true)
            ->withCount([
                'posts' => function ($query) {
                    $query->where('status', 'published');
                }
            ])
            ->latest()
            ->get();

        $featuredPosts = Post::query()
            ->where('is_featured', true)
            ->where('status', 'published')
            ->with('category')
            ->latest()
            ->get();

        $latestPosts = Post::query()
            ->where('status', 'published')
            ->with('category')
            ->latest()
            ->get();

        return Inertia::render('Frontend/Blog/BlogPage', [
            'posts' => $posts,
            'categories' => $categories,
            'featuredPosts' => $featuredPosts,
            'latestPosts' => $latestPosts,
            'filters' => $request->only(['search', 'category_id'])
        ]);
    }

    public function show(Post $blog)
    {
        $blog->load([
            'author',
            'category',
            'tags',
            'comments.user'
        ]);

        $previous = Post::query()
            ->where('status', 'published')
            ->where('published_at', '<', $blog->published_at)
            ->latest('published_at')
            ->first();

        $next = Post::query()
            ->where('status', 'published')
            ->where('published_at', '>', $blog->published_at)
            ->oldest('published_at')
            ->first();

        $categories = PostCategory::query()
            ->where('status', true)
            ->withCount([
                'posts' => function ($query) {
                    $query->where('status', 'published');
                }
            ])
            ->orderBy('name')
            ->get();


        return Inertia::render('Frontend/Blog/BlogDetailsPage', [
            'blog' => $blog,
            'previous' => $previous,
            'next' => $next,
            'categories' => $categories
        ]);
    }
}
