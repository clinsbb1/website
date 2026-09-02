<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Rules\ValidTiptapDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $article = $this->route('article');
        $publishing = $this->input('status') === Article::STATUS_PUBLISHED;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255', 'alpha_dash',
                Rule::unique('articles', 'slug')->ignore($article?->id),
            ],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content_json' => ['required', new ValidTiptapDocument(requireContent: $publishing)],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in([Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIVED])],
            'featured' => ['sometimes', 'boolean'],
            'feature_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_feature_image' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'medium_url' => ['nullable', 'url', 'max:255'],
            'devto_url' => ['nullable', 'url', 'max:255'],
            'paragraph_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
