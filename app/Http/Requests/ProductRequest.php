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
            'short_description' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'status' => ['required', Rule::in([
                Product::STATUS_LIVE, Product::STATUS_IN_DEVELOPMENT, Product::STATUS_IN_PROGRESS, Product::STATUS_ARCHIVED,
            ])],
            'website_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['sometimes', 'boolean'],
            'published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'metrics' => ['nullable', 'string', 'max:255'],
            'technologies' => ['nullable', 'string', 'max:500'],
            'case_study_enabled' => ['sometimes', 'boolean'],
            'problem' => ['nullable', 'string'],
            'role' => ['nullable', 'string'],
            'what_we_built' => ['nullable', 'string'],
            'technical_approach' => ['nullable', 'string'],
            'outcome' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
            'remove_og_image' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
