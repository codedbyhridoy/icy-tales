<?php

namespace App\Http\Controllers;

use App\Gateways\StripeGateway;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\SSLCommerzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use RuntimeException;

class PaymentController extends Controller
{
    /**
     * Redirect customer to SSLCommerz.
     */
    public function sslcommerz(
        Order $order,
        SSLCommerzService $sslcommerz
    ) {
        $payment = $this->latestPayment($order);

        if ($payment?->status === 'paid') {
            return redirect()->route('status.order.success');
        }

        $response = $sslcommerz->makePayment($order);

        if (
            isset($response['status']) &&
            $response['status'] === 'SUCCESS' &&
            isset($response['GatewayPageURL'])
        ) {
            return Redirect::away($response['GatewayPageURL']);
        }

        return Redirect::back()->with(
            'error',
            'Unable to initialize payment.'
        );
    }

    /**
     * Stripe success callback.
     */
    public function stripeSuccess(
        Payment $payment,
        Request $request,
        StripeGateway $stripeGateway,
        CartService $cartService
    ) {
        $payment->loadMissing('order.orderItems');

        if ($payment->status === 'paid') {
            $cartService->clear();

            return Redirect::route('status.order.success');
        }

        if (! $stripeGateway->verifyPayment($payment)) {
            $this->markPaymentFailed($payment, [
                'stripe' => [
                    'reason' => 'verification_failed_on_success_redirect',
                    'session_id' => $request->string('session_id')->toString(),
                ],
            ]);

            return Redirect::route('checkout.index')->with(
                'error',
                'Stripe payment verification failed.'
            );
        }

        $this->finalizePayment($payment, [
            'transaction_id' => $payment->transaction_id,
            'provider_payment_id' => $payment->provider_payment_id,
            'metadata' => [
                'stripe' => [
                    'session_id' => $request->string('session_id')->toString(),
                    'source' => 'success_redirect',
                ],
            ],
        ]);

        $cartService->clear();

        return Redirect::route('status.order.success');
    }

    /**
     * Stripe cancel callback.
     */
    public function stripeCancel(
        Payment $payment,
        Request $request
    ) {
        if ($payment->status === 'paid') {
            return Redirect::route('status.order.success');
        }

        $this->markPaymentCancelled($payment, [
            'stripe' => [
                'session_id' => $request->string('session_id')->toString(),
            ],
        ]);

        return Redirect::route('checkout.index')->with(
            'warning',
            'Payment cancelled.'
        );
    }

    /**
     * Stripe webhook.
     */
    public function stripeWebhook(
        Request $request,
        StripeGateway $stripeGateway
    ) {
        $webhook = $stripeGateway->handleWebhook($request);

        if ($webhook instanceof \Illuminate\Http\JsonResponse) {
            return $webhook;
        }

        if (! data_get($webhook, 'handled')) {
            return response()->json([
                'status' => 'ignored',
            ]);
        }

        $paymentId = data_get($webhook, 'payment_id');

        if (! $paymentId) {
            return response()->json([
                'status' => 'ok',
                'message' => 'No payment reference found in webhook payload.',
            ]);
        }

        $payment = Payment::query()
            ->with(['order.orderItems'])
            ->find($paymentId);

        if (! $payment) {
            return response()->json([
                'status' => 'payment_not_found',
            ], 404);
        }

        if ($payment->status === 'paid') {
            return response()->json([
                'status' => 'ok',
                'idempotent' => true,
            ]);
        }

        $type = (string) data_get($webhook, 'type', '');

        if (str_contains($type, 'failed')) {
            $this->markPaymentFailed($payment, [
                'stripe' => array_filter([
                    'event_id' => data_get($webhook, 'event_id'),
                    'type' => $type,
                    'session_id' => data_get($webhook, 'session_id'),
                    'payment_intent_id' => data_get($webhook, 'payment_intent_id'),
                ]),
            ]);

            return response()->json([
                'status' => 'failed',
            ]);
        }

        if (! $stripeGateway->verifyPayment($payment)) {
            return response()->json([
                'status' => 'verification_failed',
            ], 422);
        }

        $this->finalizePayment($payment, [
            'transaction_id' => data_get($webhook, 'payment_intent_id', $payment->transaction_id),
            'provider_payment_id' => data_get($webhook, 'session_id', $payment->provider_payment_id),
            'metadata' => [
                'stripe' => array_filter([
                    'event_id' => data_get($webhook, 'event_id'),
                    'type' => $type,
                    'session_id' => data_get($webhook, 'session_id'),
                    'payment_intent_id' => data_get($webhook, 'payment_intent_id'),
                    'source' => 'webhook',
                ]),
            ],
        ]);

        return response()->json([
            'status' => 'ok',
        ]);
    }

    /**
     * Payment Success for SSLCommerz.
     */
    public function success(
        Request $request,
        SSLCommerzService $sslcommerz,
        CartService $cartService
    ) {
        $order = Order::with(['orderItems', 'latestPayment'])
            ->where('order_number', $request->tran_id)
            ->firstOrFail();

        $payment = $this->latestPayment($order);

        if ($payment?->status === 'paid') {
            $cartService->clear();

            return Redirect::route('status.order.success');
        }

        $validation = $sslcommerz->validatePayment(
            $request->val_id
        );

        if (
            !isset($validation['status']) ||
            $validation['status'] !== 'VALID'
        ) {
            if ($payment) {
                $this->markPaymentFailed($payment, [
                    'sslcommerz' => $validation,
                ]);
            }

            return Redirect::route('checkout.index')
                ->with(
                    'error',
                    'Payment verification failed.'
                );
        }

        if ($payment) {
            $this->finalizePayment($payment, [
                'transaction_id' => data_get($validation, 'bank_tran_id', $request->val_id),
                'provider_payment_id' => data_get($validation, 'bank_tran_id', $request->tran_id),
                'metadata' => [
                    'sslcommerz' => $validation,
                ],
            ]);
        }

        $cartService->clear();

        return Redirect::route('status.order.success');
    }

    /**
     * Payment Failed.
     */
    public function fail(Request $request)
    {
        $order = Order::with('latestPayment')
            ->where(
                'order_number',
                $request->tran_id
            )->first();

        $payment = $order ? $this->latestPayment($order) : null;

        if ($payment) {
            $this->markPaymentFailed($payment, [
                'sslcommerz' => [
                    'reason' => 'gateway_failure',
                    'tran_id' => $request->tran_id,
                ],
            ]);
        }

        return Redirect::route('checkout.index')
            ->with(
                'error',
                'Payment failed.'
            );
    }

    /**
     * Customer cancelled payment.
     */
    public function cancel(Request $request)
    {
        $order = Order::with('latestPayment')
            ->where(
                'order_number',
                $request->tran_id
            )->first();

        $payment = $order ? $this->latestPayment($order) : null;

        if ($payment) {
            $this->markPaymentCancelled($payment, [
                'sslcommerz' => [
                    'tran_id' => $request->tran_id,
                ],
            ]);
        }

        return Redirect::route('checkout.index')
            ->with(
                'warning',
                'Payment cancelled.'
            );
    }

    /**
     * SSLCommerz IPN.
     */
    public function ipn(Request $request)
    {
        $order = Order::where(
            'order_number',
            $request->tran_id
        )->first();

        if (! $order) {
            return response()->json([
                'status' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'status' => 'OK',
        ]);
    }

    private function latestPayment(Order $order): ?Payment
    {
        return $order->relationLoaded('latestPayment')
            ? $order->latestPayment
            : $order->latestPayment()->first();
    }

    private function finalizePayment(
        Payment $payment,
        array $context = []
    ): bool {
        return DB::transaction(function () use ($payment, $context) {
            $payment = Payment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === 'paid') {
                return false;
            }

            $order = Order::query()
                ->with('orderItems')
                ->whereKey($payment->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            foreach ($order->orderItems as $item) {
                $variant = ProductVariant::query()
                    ->lockForUpdate()
                    ->find($item->product_variant_id);

                if (! $variant) {
                    continue;
                }

                if ($variant->stock < $item->quantity) {
                    throw new RuntimeException('Insufficient stock.');
                }

                $variant->decrement(
                    'stock',
                    $item->quantity
                );
            }

            $payment->forceFill([
                'status' => 'paid',
                'paid_at' => now(),
                'transaction_id' => $context['transaction_id'] ?? $payment->transaction_id,
                'provider_payment_id' => $context['provider_payment_id'] ?? $payment->provider_payment_id,
                'metadata' => array_merge(
                    $payment->metadata ?? [],
                    $context['metadata'] ?? []
                ),
            ])->save();

            $order->update([
                'status' => 'processing',
            ]);

            return true;
        });
    }

    private function markPaymentFailed(
        Payment $payment,
        array $metadata = []
    ): bool {
        return $this->updatePaymentState(
            $payment,
            'failed',
            $metadata
        );
    }

    private function markPaymentCancelled(
        Payment $payment,
        array $metadata = []
    ): bool {
        return $this->updatePaymentState(
            $payment,
            'cancelled',
            $metadata
        );
    }

    private function updatePaymentState(
        Payment $payment,
        string $status,
        array $metadata = []
    ): bool {
        return DB::transaction(function () use (
            $payment,
            $status,
            $metadata
        ) {
            $payment = Payment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === 'paid') {
                return false;
            }

            $payment->forceFill([
                'status' => $status,
                'metadata' => array_merge(
                    $payment->metadata ?? [],
                    $metadata
                ),
            ])->save();

            return true;
        });
    }
}
