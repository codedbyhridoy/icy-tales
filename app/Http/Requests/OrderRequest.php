<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'state_id' => [
                'required',
                'exists:states,id',
            ],

            'city_id' => [
                'required',
                Rule::exists('cities', 'id')
                    ->where('state_id', $this->state_id),
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'postal_code' => [
                'nullable',
                'string',
            ],

            'payment_method' => [
                'required',
                Rule::in([
                    'cod',
                    'sslcommerz',
                    'stripe',
                    'paypal',
                    'bkash',
                ]),
            ],
        ];
    }
}
