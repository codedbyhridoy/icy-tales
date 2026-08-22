<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $post = $this->route('blog');

        return [
            'category_id' => ['required', 'exists:post_categories,id'],

            'title' => ['required', 'string', 'max:255'],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($post?->id),
            ],

            'tags' => ['nullable', 'array'],

            'tags.*' => [
                'exists:tags,id',
            ],

            'excerpt' => ['nullable', 'string'],

            'content' => ['required', 'string'],

            'featured_image' => [
                $post ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_featured' => ['required', 'boolean'],

            'status' => [
                'required',
                Rule::in(['draft', 'published']),
            ],
        ];
    }
}
