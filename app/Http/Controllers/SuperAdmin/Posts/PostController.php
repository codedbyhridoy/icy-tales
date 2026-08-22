<?php

namespace App\Http\Controllers\SuperAdmin\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\PostCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $posts = Post::query()
            ->with([
                'author',
                'category',
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tags = Tag::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('SuperAdmin/Posts/Blogs/Index', [
            'blogs' => $posts,
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $categories = PostCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $tags = Tag::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('SuperAdmin/Posts/Blogs/Create', [
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        DB::beginTransaction();

        $imagePath = null;

        try {

            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')
                    ->store('posts', 'public');
            }

            $published_at = $request->status === 'published' ? now() : null;

            $post = Post::create([
                'author_id' => auth('admin')->id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $request->slug,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'featured_image' => $imagePath,
                'is_featured' => $request->is_featured,
                'status' => $request->status,
                'published_at' => $published_at
            ]);

            $post->tags()->attach($request->tags ?? []);

            DB::commit();

            return Redirect::route('super_admin.blogs.index')
                ->with('success', 'Blog created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            report($e);

            throw ValidationException::withMessages([
                'general' => __('Something went wrong. Please try again later.')
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $blog)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $blog->load([
            'author',
            'category',
            'show'
        ]);

        return Inertia::render('SuperAdmin/Posts/Blogs/Show', [
            'blog' => $blog
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $blog)
    {
        $categories = PostCategory::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $tags = Tag::query()
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $blog->load('tags:id');

        return Inertia::render('SuperAdmin/Posts/Blogs/Edit', [
            'blog' => $blog,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $blog)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        DB::beginTransaction();

        $imagePath = null;

        try {
            if ($request->hasFile('featured_image')) {
                if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                    Storage::disk('public')->delete($blog->image);
                }
                $imagePath = $request->file('featured_image')->store('posts', 'public');
            }

            $published_at = $blog->published_at;

            if ($request->status === 'published' && is_null($blog->published_at)) {
                $published_at = now();
            }

            if ($request->status === 'draft') {
                $published_at = null;
            }

            $blog->update([
                'author_id' => auth()->id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => $request->slug,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'featured_image' => $imagePath,
                'is_featured' => $request->is_featured,
                'status' => $request->status,
                'published_at' => $published_at
            ]);

            $blog->tags()->sync($request->tags ?? []);

            DB::commit();

            return Redirect::route('super_admin.blogs.index')
                ->with('success', 'Blog updated successfully.');
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $blog)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_iamge);
        }

        $blog->delete();

        return Redirect::back()->with('success', 'Blog deleted successfully.');
    }
}
