<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string|null $description
 * @property string|null $case_study_html
 * @property string|null $category
 * @property string|null $organization
 * @property string|null $problem
 * @property string|null $role
 * @property string|null $approach
 * @property string|null $challenges
 * @property string|null $results
 * @property array<int, string>|null $technologies
 * @property string|null $live_url
 * @property string|null $source_url
 * @property string|null $image_url
 * @property string|null $card_image_url
 * @property bool $is_featured
 * @property bool $is_published
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable([
    'title',
    'slug',
    'summary',
    'description',
    'case_study_html',
    'category',
    'organization',
    'problem',
    'role',
    'approach',
    'challenges',
    'results',
    'technologies',
    'live_url',
    'source_url',
    'image_url',
    'card_image_url',
    'is_featured',
    'is_published',
    'sort_order',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Scope a query to published projects.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to featured projects.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to display order.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
