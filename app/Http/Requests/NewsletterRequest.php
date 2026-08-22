<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'source' => ['nullable', 'string', 'max:50'],
            'privacy_policy' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'privacy_policy.accepted' =>
                'You must accept the Privacy Policy before subscribing.',
        ];
    }
}