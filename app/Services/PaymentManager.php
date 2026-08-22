<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use InvalidArgumentException;

class PaymentManager
{
    public function gateway(string $name): PaymentGateway
    {
        return match ($name) {
            'stripe' => app(\App\Gateways\StripeGateway::class),

            'paypal' => app(\App\Gateways\PayPalGateway::class),

            'bkash' => app(\App\Gateways\BkashGateway::class),

            default => throw new InvalidArgumentException(
                "Unsupported payment gateway: {$name}"
            ),
        };
    }
}