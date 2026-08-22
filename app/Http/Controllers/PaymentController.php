<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\SSLCommerzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    /**
     * Redirect customer to SSLCommerz
     */
    public function sslcommerz(
        Order $order,
        SSLCommerzService $sslcommerz
    ) {

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.success', $order);
        }

        $response = $sslcommerz->makePayment($order);
        dd($response);

        if (
            isset($response['status']) &&
            $response['status'] === 'SUCCESS'
        ) {
            return Redirect::away(
                $response['GatewayPageURL']
            );
        }

        return Redirect::back()->with(
            'error',
            'Unable to initialize payment.'
        );
    }

    /**
     * Payment Success
     */
    public function success(
        Request $request,
        SSLCommerzService $sslcommerz,
        CartService $cartService
    ) {

        $order = Order::with('items')
            ->where('order_number', $request->tran_id)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return Redirect::route('order.success', $order);
        }

        $validation = $sslcommerz->validatePayment(
            $request->val_id
        );

        if (
            !isset($validation['status']) ||
            $validation['status'] !== 'VALID'
        ) {

            return Redirect::route('checkout.index')
                ->with(
                    'error',
                    'Payment verification failed.'
                );
        }

        DB::transaction(function () use (
            $order,
            $cartService
        ) {

            foreach ($order->items as $item) {

                $variant = ProductVariant::lockForUpdate()
                    ->find($item->product_variant_id);

                if (!$variant) {
                    continue;
                }

                if ($variant->stock < $item->quantity) {

                    throw new \Exception(
                        'Insufficient stock.'
                    );
                }

                $variant->decrement(
                    'stock',
                    $item->quantity
                );
            }

            $order->update([

                'payment_status' => 'paid',

                'status' => 'processing',

            ]);

            $cartService->clear();
        });

        return Redirect::route(
            'order.success',
            $order
        );
    }

    /**
     * Payment Failed
     */
    public function fail(Request $request)
    {

        $order = Order::where(
            'order_number',
            $request->tran_id
        )->first();

        if ($order) {

            $order->update([

                'payment_status' => 'failed',

            ]);
        }

        return Redirect::route('checkout.index')
            ->with(
                'error',
                'Payment failed.'
            );
    }

    /**
     * Customer cancelled payment
     */
    public function cancel(Request $request)
    {
        $order = Order::where(
            'order_number',
            $request->tran_id
        )->first();

        if ($order) {
            $order->update([
                'payment_status' => 'cancelled',
            ]);
        }

        return Redirect::route('checkout.index')
            ->with(
                'warning',
                'Payment cancelled.'
            );
    }

    /**
     * SSLCommerz IPN
     */
    public function ipn(Request $request)
    {

        $order = Order::where(
            'order_number',
            $request->tran_id
        )->first();

        if (! $order) {

            return response()->json([
                'status' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => 'OK'
        ]);
    }
}
