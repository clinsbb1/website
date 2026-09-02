<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255', 'alpha_dash',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in([
                Product::STATUS_LIVE, Product::STATUS_IN_DEVELOPMENT, Product::STATUS_IN_PROGRESS, Product::STATUS_ARCHIVED,
            ])],
            'website_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['sometimes', 'boolean'],
            'published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
