<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const STATUS_LIVE = 'Live';

    public const STATUS_IN_DEVELOPMENT = 'In development';

    public const STATUS_IN_PROGRESS = 'In progress';

    public const STATUS_ARCHIVED = 'Archived';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'overview',
        'status',
        'website_url',
        'featured',
        'published',
        'sort_order',
        'metrics',
        'technologies',
        'case_study_enabled',
        'problem',
        'role',
        'what_we_built',
        'technical_approach',
        'outcome',
        'image',
        'og_image',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published' => 'boolean',
        'case_study_enabled' => 'boolean',
        'technologies' => 'array',
        'sort_order' => 'integer',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function hasCaseStudy(): bool
    {
        return $this->case_study_enabled && (
            filled($this->overview)
            || filled($this->problem)
            || filled($this->what_we_built)
            || filled($this->technical_approach)
            || filled($this->outcome)
        );
    }
}
