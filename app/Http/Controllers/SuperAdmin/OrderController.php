<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage orders')) {
            abort(403);
        }

        $filters = $request->only([
            'search',
            'status',
            'payment_status',
            'payment_method',
            'state_id',
            'city_id',
            'from_date',
            'to_date',
            'min_total',
            'max_total',
            'sort_by',
            'sort_type',
        ]);

        $orders = Order::query()
            ->with(['city.state', 'latestPayment'])
            ->filter($filters)
            ->latest()
            ->paginate($request->per_page ?? 12)
            ->withQueryString();

        return Inertia::render('SuperAdmin/Orders/Index', [
            'orders' => $orders,
            'filters' => $filters
        ]);
    }

    public function show(Order $order)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage orders')) {
            abort(403);
        }

        $order->load([
            'city.state',
            'orderItems.product.images',
            'latestPayment',
        ]);

        return Inertia::render('SuperAdmin/Orders/Show', [
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (! auth()->guard('admin')->user()->hasPermissionto('manage orders')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'processing',
                    'shipped',
                    'delivered',
                    'cancelled',
                ]),
            ],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return Redirect::back()->with(
            'success',
            'Order status updated successfully.'
        );
    }
}
