<?php

namespace App\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Payment;
use Illuminate\Http\Request;

class BkashGateway implements PaymentGateway
{
    public function createPayment(Payment $payment): mixed
    {
        return response()->json([
            'message' => 'bKash payment creation reached successfully.',
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
        ]);
    }

    public function verifyPayment(Payment $payment): bool
    {
        return false;
    }

    public function refundPayment(
        Payment $payment,
        ?float $amount = null
    ): bool {
        return false;
    }

    public function handleWebhook(Request $request): mixed
    {
        return response()->json([
            'message' => 'bKash webhook received.',
        ]);
    }
}