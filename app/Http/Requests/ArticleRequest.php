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
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', Rule::in([Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIVED])],
            'featured' => ['sometimes', 'boolean'],
            'feature_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_feature_image' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'external_platform' => [
                'nullable', 'required_with:external_url',
                Rule::in(array_keys(Article::externalPlatformLabels())),
            ],
            'external_url' => ['nullable', 'required_with:external_platform', 'url', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'content_json' => 'article content',
            'category_id' => 'category',
            'feature_image' => 'feature image',
            'published_at' => 'published at',
            'seo_title' => 'SEO title',
            'seo_description' => 'SEO description',
            'canonical_url' => 'canonical URL',
            'external_platform' => 'external platform',
            'external_url' => 'external URL',
        ];
    }
}
