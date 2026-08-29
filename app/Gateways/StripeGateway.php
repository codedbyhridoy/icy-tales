<?php

namespace App\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use RuntimeException;

class StripeGateway implements PaymentGateway
{
    private const STRIPE_API_BASE = 'https://api.stripe.com/v1';

    public function createPayment(Payment $payment): mixed
    {
        $payment->loadMissing('order.city');

        $session = $this->createCheckoutSession($payment);

        $payment->forceFill([
            'provider_payment_id' => $session['id'] ?? $payment->provider_payment_id,
            'transaction_id' => data_get($session, 'payment_intent'),
            'metadata' => array_merge(
                $payment->metadata ?? [],
                [
                    'stripe' => $session,
                ]
            ),
        ])->save();

        if (!isset($session['url'])) {
            throw new RuntimeException('Stripe did not return a checkout URL.');
        }

        return Redirect::away($session['url']);
    }

    public function verifyPayment(Payment $payment): bool
    {
        try {
            if (! $payment->provider_payment_id) {
                return false;
            }

            $session = $this->retrieveCheckoutSession($payment);

            return in_array(
                data_get($session, 'payment_status'),
                ['paid', 'no_payment_required'],
                true
            ) ||
                data_get($session, 'payment_intent.status') === 'succeeded';
        } catch (\Throwable) {
            return false;
        }
    }

    public function refundPayment(
        Payment $payment,
        ?float $amount = null
    ): bool {
        return false;
    }

    public function handleWebhook(Request $request): mixed
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (! $this->isValidWebhookSignature($payload, $signature)) {
            return response()->json([
                'message' => 'Invalid Stripe signature.',
            ], 400);
        }

        $event = json_decode($payload, true) ?: [];
        $type = data_get($event, 'type');

        if (! in_array($type, [
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded',
            'payment_intent.succeeded',
            'checkout.session.async_payment_failed',
            'payment_intent.payment_failed',
        ], true)) {
            return [
                'handled' => false,
                'type' => $type,
                'event' => $event,
            ];
        }

        $session = data_get($event, 'data.object', []);

        return [
            'handled' => true,
            'type' => $type,
            'event_id' => data_get($event, 'id'),
            'session' => $session,
            'payment_id' => $this->extractPaymentId($session),
            'session_id' => data_get($session, 'id'),
            'payment_intent_id' => data_get($session, 'payment_intent'),
            'payment_status' => data_get($session, 'payment_status'),
        ];
    }

    private function createCheckoutSession(Payment $payment): array
    {
        $response = $this->stripeRequest()->asForm()->post(
            $this->stripeApiUrl('/checkout/sessions'),
            $this->checkoutSessionPayload($payment)
        )->throw();

        return $response->json();
    }

    private function retrieveCheckoutSession(Payment $payment): array
    {
        $response = $this->stripeRequest()->get(
            $this->stripeApiUrl('/checkout/sessions/' . $payment->provider_payment_id),
            [
                'expand[]' => 'payment_intent',
            ]
        )->throw();

        return $response->json();
    }

    private function checkoutSessionPayload(Payment $payment): array
    {
        $order = $payment->order;
        $currency = strtolower((string) config('services.stripe.currency', 'usd'));
        $successUrl = route('payment.stripe.success', [
            'payment' => $payment->id,
        ]) . '?session_id={CHECKOUT_SESSION_ID}';

        return [
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => route('payment.stripe.cancel', [
                'payment' => $payment->id,
            ]),
            'client_reference_id' => (string) $payment->id,
            'payment_intent_data' => [
                'metadata' => $this->stripeMetadata($payment),
            ],
            'metadata' => $this->stripeMetadata($payment),
            'line_items[0][price_data][currency]' => $currency,
            'line_items[0][price_data][product_data][name]' => 'Order ' . $order->order_number,
            'line_items[0][price_data][product_data][description]' => $this->productDescription($order),
            'line_items[0][price_data][unit_amount]' => $this->minorAmount($payment),
            'line_items[0][quantity]' => 1,
        ];
    }

    private function productDescription(Order $order): string
    {
        return trim(sprintf(
            '%s %s | %s',
            $order->first_name,
            $order->last_name ?? '',
            $order->city?->name ?? $order->address
        ));
    }

    private function stripeMetadata(Payment $payment): array
    {
        return [
            'payment_id' => (string) $payment->id,
            'order_id' => (string) $payment->order_id,
            'order_number' => $payment->order?->order_number ?? '',
        ];
    }

    private function stripeRequest(): PendingRequest
    {
        $secret = config('services.stripe.secret');

        if (! $secret) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        return Http::withBasicAuth($secret, '');
    }

    private function stripeApiUrl(string $path): string
    {
        return self::STRIPE_API_BASE . $path;
    }

    private function minorAmount(Payment $payment): int
    {
        return (int) round(((float) $payment->amount) * 100);
    }

    private function extractPaymentId(array $session): ?int
    {
        $paymentId = data_get($session, 'metadata.payment_id')
            ?? data_get($session, 'client_reference_id');

        return is_numeric($paymentId) ? (int) $paymentId : null;
    }

    private function isValidWebhookSignature(
        string $payload,
        ?string $signatureHeader
    ): bool {
        $secret = config('services.stripe.webhook_secret');

        if (! $secret || ! $signatureHeader) {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $signatureHeader) as $pair) {
            [$key, $value] = array_pad(explode('=', trim($pair), 2), 2, null);

            if ($key === 't') {
                $timestamp = is_numeric($value) ? (int) $value : null;
                continue;
            }

            if ($key === 'v1' && $value) {
                $signatures[] = $value;
            }
        }

        if (! $timestamp || empty($signatures)) {
            return false;
        }

        if (abs(time() - $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }
}
