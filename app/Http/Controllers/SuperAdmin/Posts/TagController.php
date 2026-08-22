<?php

namespace App\Http\Controllers\SuperAdmin\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use Illuminate\Support\Facades\Redirect;
use App\Models\Tag;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('SuperAdmin/Posts/Tags/Index', [
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('SuperAdmin/Posts/Tags/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $data = $request->validated();

        Tag::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status']
        ]);


        return Redirect::route('super_admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return Inertia::render('SuperAdmin/Posts/Tags/Edit', [
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $data = $request->validated();

        $tag->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status']
        ]);


        return Redirect::route('super_admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if ($tag->posts()->exists()) {

            return Redirect::back()->with(
                'error',
                'This tag is assigned to one or more posts and cannot be deleted.'
            );
        }

        $tag->delete();

        return Redirect::back()
            ->with('success', 'Tag deleted successfully.');
    }
}
