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

    public function seoTitle(): string
    {
        if ($this->seo_title) {
            return $this->seo_title;
        }
        $suffix = $this->location ? " in {$this->location}, Pretoria East" : ' in Pretoria East';
        return $this->title . $suffix . ' | RDM Developments';
    }

    public function metaDescription(): string
    {
        return $this->meta_description
            ?: Str::limit(strip_tags($this->description ?: ''), 155);
    }

    public function hasBeforeAfter(): bool
    {
        return $this->beforeImages()->exists() && $this->afterImages()->exists();
    }
}
