<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Member;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        $filters = $request->only([
            'search',
            'is_active',
            'sort_by',
            'designation',
        ]);

        $members = Member::query()
            ->latest()
            ->filter($filters)
            ->paginate($request->per_page ?? 12);

        return Inertia::render('SuperAdmin/Teams/Index', [
            'members' => $members,
            'filters' => $filters
        ]);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        return Inertia::render('SuperAdmin/Teams/Create');
    }

    /**
     * Store a new member.
     */
    public function store(Request $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'facebook_url' => ['nullable', 'string', 'max:5000'],
            'instagram_url' => ['nullable', 'string', 'max:5000'],
            'youtube_url' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('team-members', 'public');
        }

        Member::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'designation' => $request->designation,
            'bio' => $request->bio,
            'image' => $imagePath,
            'facebook' => $request->facebook_url,
            'instagram' => $request->instagram_url,
            'youtube' => $request->youtube_url,
            'is_active' => $request->is_active
        ]);

        return Redirect::route('super_admin.team-members.index');
    }

    /**
     * Show a specific member.
     */
    public function show(Member $team_member)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        return Inertia::render('SuperAdmin/Teams/Show', [
            'member' => $team_member
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit(Member $team_member)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        return Inertia::render('SuperAdmin/Teams/Edit', [
            'member' => $team_member
        ]);
    }

    /**
     * Update member.
     */

    public function update(Request $request, Member $team_member)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'facebook_url' => ['nullable', 'string', 'max:5000'],
            'instagram_url' => ['nullable', 'string', 'max:5000'],
            'youtube_url' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $imagePath = $team_member->image;

        if ($request->hasFile('image')) {
            if ($team_member->image && Storage::disk('public')->exists($team_member->image)) {
                Storage::disk('public')->delete($team_member->image);
            }
            $imagePath = $request->file('image')->store('team-members', 'public');
        }

        $team_member->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'designation' => $request->designation,
            'bio' => $request->bio,
            'image' => $imagePath,
            'facebook' => $request->facebook_url,
            'instagram' => $request->instagram_url,
            'youtube' => $request->youtube_url,
            'is_active' => $request->is_active
        ]);

        return Redirect::route('super_admin.team-members.index');
    }

    /**
     * Destroy member.
     */

    public function destroy(Member $team_member)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage members')) {
            abort(403);
        }

        if ($team_member->image && Storage::disk('public')->exists($team_member->image)) {
            Storage::disk('public')->delete($team_member->image);
        }

        return Redirect::back()
            ->with('success', 'You have successfully deleted a member');
    }
}
