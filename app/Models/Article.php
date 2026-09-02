<?php

namespace App\Models;

use App\Support\TiptapRenderer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Article extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content_json',
        'category',
        'status',
        'featured',
        'feature_image',
        'published_at',
        'seo_title',
        'seo_description',
        'canonical_url',
        'medium_url',
        'devto_url',
        'paragraph_url',
    ];

    protected $casts = [
        'content_json' => 'array',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    private ?string $renderedContentCache = null;

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where('published_at', '<=', Carbon::now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $words = str_word_count(TiptapRenderer::toPlainText($this->content_json));

                return max(1, (int) ceil($words / 200));
            },
        );
    }

    protected function resolvedCanonicalUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->canonical_url ?: route('writing.show', $this->slug),
        );
    }

    protected function renderedContent(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->renderedContentCache === null) {
                    $this->renderedContentCache = TiptapRenderer::render($this->content_json);
                }

                return $this->renderedContentCache;
            },
        );
    }

    protected function ogImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->feature_image
                ? asset('storage/'.$this->feature_image)
                : asset('clinton_og.jpg'),
        );
    }
}
