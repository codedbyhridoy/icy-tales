<?php

use App\Gateways\StripeGateway;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

it('creates a stripe checkout session and stores provider identifiers', function () {
    config()->set('services.stripe.secret', 'sk_test_123');
    config()->set('services.stripe.currency', 'usd');

    $fixture = stripePaymentFixture();

    Http::fake(function ($request) use ($fixture) {
        if (
            $request->method() === 'POST' &&
            str_contains($request->url(), '/checkout/sessions')
        ) {
            $payload = $request->data();

            expect(data_get($payload, 'success_url'))
                ->toContain('{CHECKOUT_SESSION_ID}');
            expect(data_get($payload, 'metadata.payment_id'))
                ->toBe((string) $fixture['payment']->id);

            return Http::response([
                'id' => 'cs_test_session_123',
                'url' => 'https://checkout.stripe.com/pay/cs_test_session_123',
                'payment_intent' => 'pi_test_intent_123',
            ]);
        }

        return Http::response([], 404);
    });

    $response = app(StripeGateway::class)->createPayment(
        $fixture['payment']->fresh()
    );

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())
        ->toBe('https://checkout.stripe.com/pay/cs_test_session_123');

    $fixture['payment']->refresh();

    expect($fixture['payment']->provider_payment_id)
        ->toBe('cs_test_session_123');
    expect($fixture['payment']->transaction_id)
        ->toBe('pi_test_intent_123');
    expect($fixture['payment']->metadata)
        ->toHaveKey('stripe');
});

it('finalizes a stripe payment once across the success return and webhook', function () {
    config()->set('services.stripe.secret', 'sk_test_123');
    config()->set('services.stripe.webhook_secret', 'whsec_test');
    config()->set('services.stripe.currency', 'usd');

    $fixture = stripePaymentFixture([
        'provider_payment_id' => 'cs_test_session_123',
        'transaction_id' => 'pi_test_intent_123',
    ]);

    Http::fake(function ($request) {
        if (
            $request->method() === 'GET' &&
            str_contains($request->url(), '/checkout/sessions/cs_test_session_123')
        ) {
            return Http::response([
                'id' => 'cs_test_session_123',
                'payment_status' => 'paid',
                'payment_intent' => [
                    'id' => 'pi_test_intent_123',
                    'status' => 'succeeded',
                ],
            ]);
        }

        return Http::response([], 404);
    });

    $this->get(
        route('payment.stripe.success', $fixture['payment'])
        . '?session_id=cs_test_session_123'
    )->assertRedirect(route('status.order.success'));

    $fixture['payment']->refresh();
    $fixture['order']->refresh();
    $fixture['variant']->refresh();

    expect($fixture['payment']->status)->toBe('paid');
    expect($fixture['order']->status)->toBe('processing');
    expect($fixture['variant']->stock)->toBe(3);

    $payload = json_encode([
        'id' => 'evt_test_123',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_session_123',
                'payment_status' => 'paid',
                'payment_intent' => [
                    'id' => 'pi_test_intent_123',
                    'status' => 'succeeded',
                ],
                'metadata' => [
                    'payment_id' => $fixture['payment']->id,
                ],
                'client_reference_id' => (string) $fixture['payment']->id,
            ],
        ],
    ], JSON_UNESCAPED_SLASHES);

    $signature = stripeWebhookSignature($payload, 'whsec_test');

    $response = $this->call(
        'POST',
        route('payment.stripe.webhook'),
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => $signature,
        ],
        $payload
    );

    $response->assertOk()->assertJson([
        'status' => 'ok',
        'idempotent' => true,
    ]);

    $fixture['variant']->refresh();
    $fixture['payment']->refresh();

    expect($fixture['variant']->stock)->toBe(3);
    expect($fixture['payment']->status)->toBe('paid');
});

it('cancels a stripe payment without touching inventory', function () {
    $fixture = stripePaymentFixture([
        'provider_payment_id' => 'cs_test_session_cancel',
        'transaction_id' => 'pi_test_intent_cancel',
    ]);

    $this->get(
        route('payment.stripe.cancel', $fixture['payment'])
        . '?session_id=cs_test_session_cancel'
    )->assertRedirect(route('checkout.index'));

    $fixture['payment']->refresh();
    $fixture['variant']->refresh();

    expect($fixture['payment']->status)->toBe('cancelled');
    expect($fixture['variant']->stock)->toBe(5);
});

it('marks a stripe payment as failed from a webhook event', function () {
    config()->set('services.stripe.webhook_secret', 'whsec_test');

    $fixture = stripePaymentFixture([
        'provider_payment_id' => 'cs_test_session_failed',
        'transaction_id' => 'pi_test_intent_failed',
    ]);

    $payload = json_encode([
        'id' => 'evt_test_failed',
        'type' => 'checkout.session.async_payment_failed',
        'data' => [
            'object' => [
                'id' => 'cs_test_session_failed',
                'payment_status' => 'unpaid',
                'payment_intent' => [
                    'id' => 'pi_test_intent_failed',
                    'status' => 'requires_payment_method',
                ],
                'metadata' => [
                    'payment_id' => $fixture['payment']->id,
                ],
                'client_reference_id' => (string) $fixture['payment']->id,
            ],
        ],
    ], JSON_UNESCAPED_SLASHES);

    $signature = stripeWebhookSignature($payload, 'whsec_test');

    $response = $this->call(
        'POST',
        route('payment.stripe.webhook'),
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => $signature,
        ],
        $payload
    );

    $response->assertOk()->assertJson([
        'status' => 'failed',
    ]);

    $fixture['payment']->refresh();
    $fixture['variant']->refresh();

    expect($fixture['payment']->status)->toBe('failed');
    expect($fixture['variant']->stock)->toBe(5);
});

function stripePaymentFixture(array $paymentOverrides = []): array
{
    $suffix = Str::lower(Str::random(8));

    $state = State::create([
        'name' => 'State ' . $suffix,
        'status' => true,
    ]);

    $city = City::create([
        'state_id' => $state->id,
        'name' => 'City ' . $suffix,
        'status' => true,
    ]);

    $category = Category::create([
        'name' => 'Category ' . $suffix,
        'slug' => 'category-' . $suffix,
        'status' => true,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Product ' . $suffix,
        'slug' => 'product-' . $suffix,
        'short_description' => 'Short description',
        'description' => 'Full description',
        'is_featured' => false,
        'is_active' => true,
    ]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'SKU-' . $suffix,
        'price' => 150,
        'stock' => 5,
    ]);

    $user = User::create([
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '01700000000',
        'email' => 'test-' . $suffix . '@example.com',
        'password' => 'password',
        'status' => true,
    ]);

    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'ORD-' . strtoupper($suffix),
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $user->email,
        'phone' => '01700000000',
        'country' => 'Bangladesh',
        'state_id' => $state->id,
        'city_id' => $city->id,
        'address' => '123 Test Street',
        'postal_code' => '1200',
        'subtotal' => 300,
        'shipping_charge' => 0,
        'discount' => 0,
        'total' => 300,
        'status' => 'pending',
    ]);

    $order->orderItems()->create([
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'product_name' => $product->name,
        'variant_name' => 'Default',
        'sku' => $variant->sku,
        'price' => 150,
        'quantity' => 2,
        'subtotal' => 300,
    ]);

    $payment = $order->payments()->create(array_merge([
        'gateway' => 'stripe',
        'amount' => 300,
        'currency' => 'usd',
        'status' => 'pending',
    ], $paymentOverrides));

    return compact(
        'state',
        'city',
        'category',
        'product',
        'variant',
        'user',
        'order',
        'payment'
    );
}

function stripeWebhookSignature(string $payload, string $secret, ?int $timestamp = null): string
{
    $timestamp ??= time();
    $signature = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);

    return 't=' . $timestamp . ',v1=' . $signature;
}
