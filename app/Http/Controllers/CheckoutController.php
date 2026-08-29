<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\State;
use App\Services\CartService;
use App\Services\PaymentManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected PaymentManager $paymentManager,
    ) {}

    public function index()
    {
        $summary = $this->cartService->summary();

        $states = Cache::remember(
            'states_with_cities',
            now()->addHours(6),
            function () {
                return State::query()
                    ->where('status', true)
                    ->with([
                        'cities' => fn ($query) => $query
                            ->where('status', true)
                            ->orderBy('name'),
                    ])
                    ->orderBy('name')
                    ->get();
            }
        );

        return Inertia::render('Frontend/CheckoutPage', [
            'items' => $this->cartService->getItems(),
            'total' => $summary['total'],
            'states' => $states,
        ]);
    }

    public function store(OrderRequest $request)
    {
        if ($this->cartService->isEmpty()) {
            return back()->withErrors([
                'checkout' => 'Your cart is empty.',
            ]);
        }

        DB::beginTransaction();

        try {
            $summary = $this->cartService->summary();
            $items = $this->cartService->items();

            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'user_id' => auth()->id(),

                'order_number' =>
                    'ORD-' .
                    now()->format('YmdHis') .
                    strtoupper(Str::random(4)),

                // Customer
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,

                // Address
                'country' => $request->country,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'postal_code' => $request->postal_code,

                // Totals
                'subtotal' => $summary['subtotal'],
                'shipping_charge' => $summary['shipping'],
                'discount' => $summary['discount'],
                'total' => $summary['total'],

                // Order
                'status' => 'pending',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Order Items
            |--------------------------------------------------------------------------
            */

            foreach ($items as $item) {

                /*
                |--------------------------------------------------------------------------
                | Check Stock
                |--------------------------------------------------------------------------
                */

                if ($item->variant) {
                    if ($item->variant->stock < $item->quantity) {
                        DB::rollBack();

                        return back()->withErrors([
                            'quantity' =>
                                "({$item->name_snapshot}) is out of stock.",
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Create Order Item
                |--------------------------------------------------------------------------
                */

                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,

                    'product_name' => $item->name_snapshot,

                    'variant_name' => collect($item->option_snapshot)
                        ->pluck('value')
                        ->implode(' / '),

                    'sku' => $item->sku_snapshot,

                    'price' => $item->price_snapshot,

                    'quantity' => $item->quantity,

                    'subtotal' =>
                        $item->price_snapshot * $item->quantity,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = $order->payments()->create([
                'gateway' => $request->payment_method,
                'amount' => $order->total,
                'currency' => 'BDT',
                'status' => 'pending',
            ]);

            // Stripe/online gateway initialization will fail here if the required
            // credentials are missing from .env, so return a clear validation error
            // instead of the generic "Something went wrong" message.
            if ($payment->gateway === 'stripe' && ! config('services.stripe.secret')) {
                DB::rollBack();

                return back()->withErrors([
                    'payment_method' =>
                        'Stripe is not configured on this server. Please choose COD or SSLCommerz.',
                ]);
            }

            if ($payment->gateway === 'sslcommerz' && (
                ! config('services.sslcommerz.store_id') ||
                ! config('services.sslcommerz.store_password')
            )) {
                DB::rollBack();

                return back()->withErrors([
                    'payment_method' =>
                        'SSLCommerz is not configured on this server. Please choose COD or Stripe.',
                ]);
            }

            DB::commit();

            if ($payment->gateway === 'cod') {
                $this->cartService->clear();

                return Redirect::route('status.order.success');
            }

            if ($payment->gateway === 'sslcommerz') {
                return Redirect::route('payment.sslcommerz', $order);
            }

            $gateway = $this->paymentManager->gateway(
                $payment->gateway
            );

            return $gateway->createPayment($payment);

        } catch (\Throwable $e) {

            DB::rollBack();

            throw ValidationException::withMessages([
                'checkout' =>
                    'Something went wrong while placing your order.',
            ]);
        }
    }
}
