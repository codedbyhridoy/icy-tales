<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Statistic;
use Inertia\Inertia;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionTo('manage statistics')) {
            abort(403);
        }

        $statistics = Statistic::where('status', true)
            ->latest()
            ->paginate($request->per_page ?? 12);

        return Inertia::render('SuperAdmin/Statistics/Index', [
            'statistics' => $statistics
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         if (! auth()->guard('admin')->user()->hasPermissionTo('manage statistics')) {
            abort(403);
        }

        return Inertia::render('SuperAdmin/Statistics/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         if (! auth()->guard('admin')->user()->hasPermissionTo('manage statistics')) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:50'],
            'suffix' => ['nullable', 'string', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean']
        ]);

        Statistic::create([
            'title' => $request->title,
            'value' => $request->value,
            'suffix' => $request->suffix,
            'sort_order' => $request->sort_order,
            'status' => $request->status
        ]);

        return Redirect::route('super_admin.statistics.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Statistic $statistic)
    {
         if (! auth()->guard('admin')->user()->hasPermissionTo('manage statistics')) {
            abort(403);
        }

        return Inertia::render('SuperAdmin/Statistics/Edit', [
            'statistic' => $statistic
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Statistic $statistic)
    {
         if (! auth()->guard('admin')->user()->hasPermissionTo('manage statistics')) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:50'],
            'suffix' => ['nullable', 'string', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean']
        ]);

        $statistic->update([
            'title' => $request->title,
            'value' => $request->value,
            'suffix' => $request->suffix,
            'sort_order' => $request->sort_order,
            'status' => $request->status
        ]);

        return Redirect::route('super_admin.statistics.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Statistic $statistic)
    {
         if (! auth()->guard('admin')->user()->hasPermissionTo('manage statistics')) {
            abort(403);
        }

        $statistic->delete();

        return Redirect::back();
    }
}
