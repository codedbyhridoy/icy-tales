<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class SSLCommerzService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.sslcommerz.sandbox')
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    public function makePayment(Order $order)
    {
        $response = Http::asForm()->post(
            $this->baseUrl . '/gwprocess/v4/api.php',
            [
                'store_id' => config('services.sslcommerz.store_id'),
                'store_passwd' => config('services.sslcommerz.store_password'),

                'total_amount' => $order->total,
                'currency' => 'BDT',

                'tran_id' => $order->order_number,

                'success_url' => route('payment.success'),
                'fail_url' => route('payment.fail'),
                'cancel_url' => route('payment.cancel'),
                'ipn_url' => route('payment.ipn'),

                'cus_name' => $order->first_name . ' ' . $order->last_name,
                'cus_email' => $order->email ?: 'customer@example.com',
                'cus_phone' => $order->phone,

                'cus_add1' => $order->address,
                'cus_city' => $order->city?->name,
                'cus_country' => $order->country,

                'shipping_method' => 'NO',

                'product_name' => 'Cake Order',
                'product_category' => 'Cake',
                'product_profile' => 'general',
            ]
        );

        return $response->json();
    }

    public function validatePayment(string $validationId)
    {
        $response = Http::get(
            $this->baseUrl . '/validator/api/validationserverAPI.php',
            [
                'val_id' => $validationId,
                'store_id' => config('services.sslcommerz.store_id'),
                'store_passwd' => config('services.sslcommerz.store_password'),
                'v' => 1,
                'format' => 'json',
            ]
        );

        return $response->json();
    }
}
