<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_featured'   => 'boolean',
        'is_published'  => 'boolean',
        'completed_on'  => 'date',
        'sort_order'    => 'integer',
    ];

    public const TYPE_RENOVATION = 'renovation';
    public const TYPE_BUILD      = 'build';

    protected static function booted(): void
    {
        static::saving(function (self $p) {
            if (empty($p->slug) && ! empty($p->title)) {
                $p->slug = Str::slug($p->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function beforeImages(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'before')->orderBy('sort_order')->orderBy('id');
    }

    public function afterImages(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'after')->orderBy('sort_order')->orderBy('id');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'gallery')->orderBy('sort_order')->orderBy('id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRenovations($query)
    {
        return $query->where('project_type', self::TYPE_RENOVATION);
    }

    public function scopeBuilds($query)
    {
        return $query->where('project_type', self::TYPE_BUILD);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('completed_on')
            ->orderByDesc('id');
    }

    public function featuredImageUrl(): ?string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        $first = $this->images()->first();
        return $first ? asset('storage/' . $first->path) : null;
    }

    /**
     * Title with trailing location phrases stripped, so H1/meta can append
     * the suburb once without "in Pretoria East in Pretoria East".
     */
    public function baseTitle(): string
    {
        $title = trim((string) $this->title);

        if ($this->location) {
            $title = preg_replace(
                '/\s+in\s+' . preg_quote($this->location, '/') . '\b/i',
                '',
                $title
            ) ?? $title;
        }

        $title = preg_replace('/\s+in\s+Pretoria\s+East\b/i', '', $title) ?? $title;

        return trim(preg_replace('/\s{2,}/', ' ', $title) ?? $title, " \t\n\r\0\x0B,-—");
    }

    /**
     * Human H1: "Complete Bathroom Renovation — Garsfontein, Pretoria East".
     */
    public function displayHeading(): string
    {
        $base = $this->baseTitle();
        $place = $this->locationLabel();

        return $place ? $base . ' — ' . $place : $base . ' — Pretoria East';
    }

    /**
     * Card/subtitle location without duplicating "Pretoria East".
     * e.g. "Garsfontein, Pretoria East" — never "Pretoria East, Pretoria East".
     */
    public function locationLabel(): ?string
    {
        $location = trim((string) $this->location);

        if ($location === '') {
            return null;
        }

        if (stripos($location, 'Pretoria East') !== false) {
            return $location;
        }

        return $location . ', Pretoria East';
    }

    public function seoTitle(): string
    {
        if ($this->seo_title) {
            return $this->seoTitleClean($this->seo_title);
        }

        return $this->displayHeading() . ' | RDM Developments';
    }

    public function metaDescription(): string
    {
        $raw = $this->meta_description
            ?: strip_tags($this->description ?: '');

        return $this->sanitizeMetaDescription($raw);
    }

    /**
     * Strip hashtags and hard-cap at ~155 characters for SERP snippets.
     */
    public function sanitizeMetaDescription(string $text): string
    {
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Remove #hashtags (and common trailing social tags).
        $text = preg_replace('/#\S+/u', '', $text) ?? $text;
        $text = preg_replace('/\s{2,}/u', ' ', $text) ?? $text;
        $text = trim($text);

        return Str::limit($text, 155, '…');
    }

    protected function seoTitleClean(string $title): string
    {
        return trim(preg_replace('/\s{2,}/', ' ', $title) ?? $title);
    }

    public function hasBeforeAfter(): bool
    {
        return $this->beforeImages()->exists() && $this->afterImages()->exists();
    }

    /**
     * First image of a given type, preferring the eager-loaded `images`
     * relation so project-card grids don't fire an extra query per card.
     */
    public function firstImageOfType(string $type): ?ProjectImage
    {
        if ($this->relationLoaded('images')) {
            return $this->images->firstWhere('type', $type);
        }

        return $this->images()->where('type', $type)->first();
    }

    /** N+1-safe variant of hasBeforeAfter() for card grids. */
    public function cardHasBeforeAfter(): bool
    {
        return $this->firstImageOfType('before') && $this->firstImageOfType('after');
    }
}
