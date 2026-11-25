<?php

namespace App\Models;

use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $headline
 * @property string|null $tagline
 * @property string|null $hero_intro
 * @property string|null $now_building
 * @property string|null $now_learning
 * @property string|null $now_cooking
 * @property string $bio
 * @property string|null $working_style
 * @property string|null $education
 * @property string|null $outside_the_code_html
 * @property string|null $location
 * @property string $email
 * @property string|null $phone
 * @property string|null $website_url
 * @property string|null $github_url
 * @property string|null $linkedin_url
 * @property bool $available_for_work
 * @property int|null $years_experience
 * @property string|null $resume_url
 * @property string|null $logo_url
 * @property string|null $headshot_url
 * @property string|null $about_image_url
 * @property string|null $favicon_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable([
    'name',
    'headline',
    'tagline',
    'hero_intro',
    'now_building',
    'now_learning',
    'now_cooking',
    'bio',
    'working_style',
    'education',
    'outside_the_code_html',
    'location',
    'email',
    'phone',
    'website_url',
    'github_url',
    'linkedin_url',
    'available_for_work',
    'years_experience',
    'resume_url',
    'logo_url',
    'headshot_url',
    'about_image_url',
    'favicon_url',
])]
class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    /**
     * Get the site profile, or a blank one before the portfolio is seeded.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new static;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'available_for_work' => 'boolean',
            'years_experience' => 'integer',
        ];
    }
}
