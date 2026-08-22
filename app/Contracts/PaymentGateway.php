<?php

namespace App\Contracts;

use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentGateway
{
    public function createPayment(Payment $payment): mixed;

    public function verifyPayment(Payment $payment): bool;

    public function refundPayment(
        Payment $payment,
        ?float $amount = null
    ): bool;

    public function handleWebhook(Request $request): mixed;
}