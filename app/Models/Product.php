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
        'description',
        'status',
        'website_url',
        'featured',
        'published',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published' => 'boolean',
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
}
