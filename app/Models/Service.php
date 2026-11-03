<?php

namespace App\Models;

use App\Support\MetaDescription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
        // Optional FAQ items: [['question' => '…', 'answer' => '…'], …]
        'faq'          => 'array',
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

    /**
     * Homepage and listing card title, without the location suffix
     * kept on the service page H1, title and meta description.
     */
    public function cardTitle(): string
    {
        $title = trim((string) preg_replace('/\s+Pretoria East\s*$/iu', '', (string) $this->title));

        return match (mb_strtolower($title)) {
            'bathroom renovations' => 'Bathroom renovations',
            default => $title,
        };
    }

    public function seoTitle(): string
    {
        return $this->seo_title ?: $this->title . ' | RDM Developments';
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function publishedProjects(): BelongsToMany
    {
        return $this->projects()->published()->ordered();
    }

    public function metaDescription(): string
    {
        return MetaDescription::from(
            $this->meta_description,
            $this->excerpt ?: $this->description
        );
    }

    public function heroImageUrl(): ?string
    {
        return $this->hero_image ? asset('storage/' . $this->hero_image) : null;
    }

    /**
     * Promote bare <h3> section headings in the rich-text body to <h2>
     * so the page outline is H1 → H2 → H3 without a skipped level.
     * Nested headings inside lists are not used in our content.
     */
    public function descriptionForDisplay(): string
    {
        $html = (string) $this->description;

        return str_replace(
            ['<h3>', '</h3>', '<h3 ', '</H3>'],
            ['<h2>', '</h2>', '<h2 ', '</h2>'],
            $html
        );
    }

    /** @return array<int, array{question: string, answer: string}> */
    public function faqItems(): array
    {
        return collect($this->faq ?? [])
            ->filter(fn ($item) => filled($item['question'] ?? null) && filled($item['answer'] ?? null))
            ->values()
            ->all();
    }
}
