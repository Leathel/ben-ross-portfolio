<?php

namespace App\Models;

use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $category
 * @property int|null $proficiency
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable(['name', 'category', 'proficiency', 'sort_order'])]
class Skill extends Model
{
    /** @use HasFactory<SkillFactory> */
    use HasFactory;

    /**
     * Scope a query to display order.
     *
     * @param  Builder<Skill>  $query
     * @return Builder<Skill>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'proficiency' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
