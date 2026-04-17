<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $service) {
            if (empty($service->slug) && ! empty($service->title)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function seoTitle(): string
    {
        return $this->seo_title ?: $this->title . ' | RDM Developments';
    }

    public function metaDescription(): string
    {
        return $this->meta_description
            ?: Str::limit(strip_tags($this->excerpt ?: $this->description ?: ''), 155);
    }

    public function heroImageUrl(): ?string
    {
        return $this->hero_image ? asset('storage/' . $this->hero_image) : null;
    }
}
