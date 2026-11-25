<?php

namespace App\Models;

use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $subject
 * @property string $message
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable(['name', 'email', 'subject', 'message', 'read_at'])]
class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    /**
     * Scope a query to unread messages.
     *
     * @param  Builder<ContactMessage>  $query
     * @return Builder<ContactMessage>
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
