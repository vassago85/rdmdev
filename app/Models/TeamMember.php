<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OverflowException;

class TeamMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $member) {
            $query = static::query();

            if ($member->page_setting_id) {
                $query->where('page_setting_id', $member->page_setting_id);
            }

            if ($query->count() >= PageSetting::MAX_TEAM_MEMBERS) {
                throw new OverflowException(
                    'The About & Team page supports a maximum of ' . PageSetting::MAX_TEAM_MEMBERS . ' team members.'
                );
            }
        });
    }

    public function pageSetting(): BelongsTo
    {
        return $this->belongsTo(PageSetting::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function photoUrl(?string $fallback = null): ?string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        return $fallback;
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name)) ?: [];

        $letters = collect($parts)
            ->filter()
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        return $letters !== '' ? $letters : '?';
    }
}
