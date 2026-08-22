<?php

namespace App\Http\Controllers\SuperAdmin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $categories = PostCategory::latest()->paginate(10);

        return Inertia::render('SuperAdmin/Posts/Categories/Index', [
            'categories' => $categories
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

        return Inertia::render('SuperAdmin/Posts/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean']
        ]);

        PostCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return Redirect::route('super_admin.blog-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCategory $blog_category)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        return Inertia::render('SuperAdmin/Posts/Categories/Edit', [
            'category' => $blog_category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostCategory $blog_category)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean']
        ]);

        $blog_category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return Redirect::route('super_admin.blog-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostCategory $blog_category)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage blog-categories')) {
            abort(403);
        }

        if ($blog_category->posts()->exists()) {
            return back()->withErrors([
                'category' => 'This category can not be deleted because it has posts.'
            ]);
        }

        $blog_category->delete();

        return Redirect::back()
            ->with('success', 'Category deleted successfully.');
    }
}
