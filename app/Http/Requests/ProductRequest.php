<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',

            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'is_featured' => 'boolean',
            'is_active' => 'boolean',

            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',

            'options' => 'nullable|array',
            'options.*.name' => 'required_with:options|string|max:100',
            'options.*.type' => 'required_with:options|string|max:50',
            'options.*.values' => 'required_with:options|array|min:1',
            'options.*.values.*' => 'required|string|max:100',

            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => ['required', 'decimal:0,2', 'min:0', 'max:999999.99',],            
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.options' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [

            'first_name.required' => 'Please enter your first name.',
            'phone.required' => 'Please enter your phone number.',
            'country.required' => 'Please select your country.',
            'city.required' => 'Please enter your city.',
            'address.required' => 'Please enter your address.',
            'payment_method.required' => 'Please choose a payment method.',

        ];
    }
}
