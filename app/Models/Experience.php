<?php

namespace App\Models;

use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $company
 * @property string $role
 * @property string|null $location
 * @property string|null $employment_type
 * @property Carbon $start_date
 * @property Carbon|null $end_date
 * @property bool $is_current
 * @property string $description
 * @property array<int, string>|null $skill_tags
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable([
    'company',
    'role',
    'location',
    'employment_type',
    'start_date',
    'end_date',
    'is_current',
    'description',
    'skill_tags',
    'sort_order',
])]
class Experience extends Model
{
    /** @use HasFactory<ExperienceFactory> */
    use HasFactory;

    /**
     * Scope a query to display order.
     *
     * @param  Builder<Experience>  $query
     * @return Builder<Experience>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('start_date');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'skill_tags' => 'array',
            'sort_order' => 'integer',
        ];
    }
}
