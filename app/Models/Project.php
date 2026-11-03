<?php

namespace App\Models;

use App\Support\MetaDescription;
use App\Support\ResponsiveImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function publishedServices(): BelongsToMany
    {
        return $this->services()->published()->ordered();
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
     * Card thumbnail. Prefers an explicit cover, then the after photo,
     * then the featured image used on the project page.
     */
    public function coverImageUrl(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        if ($after = $this->firstImageOfType('after')) {
            return $after->url();
        }

        return $this->featuredImageUrl();
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
     * Human H1. The suburb is not repeated when the title already contains it.
     * "Wingate Park Home Renovation" in Wingate Park becomes
     * "Wingate Park Home Renovation, Pretoria East".
     */
    public function displayHeading(): string
    {
        $base = $this->baseTitle();
        $suburb = $this->suburbName();
        $hasSuburb = $suburb !== '' && mb_stripos($base, $suburb) !== false;
        $hasRegion = mb_stripos($base, 'Pretoria East') !== false;

        if ($hasRegion) {
            return $base;
        }

        if ($hasSuburb || $suburb === '' || strcasecmp($suburb, 'Pretoria East') === 0) {
            return $base.', Pretoria East';
        }

        return $base.', '.$suburb.', Pretoria East';
    }

    public function suburbName(): string
    {
        $location = trim((string) $this->location);
        $location = preg_replace('/,?\s*Pretoria\s+East\s*$/i', '', $location) ?? $location;

        return trim($location, " \t,");
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
        return MetaDescription::from($this->meta_description, $this->description);
    }

    public function coverStoragePath(): ?string
    {
        if ($this->cover_image) {
            return $this->cover_image;
        }

        if ($after = $this->firstImageOfType('after')) {
            return $after->path;
        }

        if ($this->featured_image) {
            return $this->featured_image;
        }

        if ($this->relationLoaded('images')) {
            return $this->images->first()?->path;
        }

        return $this->images()->first()?->path;
    }

    /** @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool} */
    public function coverPicture(): array
    {
        return ResponsiveImage::fromStoragePath($this->coverStoragePath());
    }

    /** @return array{src: string, srcset: ?string, width: int, height: int, original_width: int, below_1200: bool} */
    public function featuredPicture(): array
    {
        $path = $this->featured_image ?: $this->coverStoragePath();

        return ResponsiveImage::fromStoragePath($path);
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
