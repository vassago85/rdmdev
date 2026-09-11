<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Singleton row (id=1) for the public About & Team page — the copy, SEO,
 * homepage teaser, and related team slots the updates person publishes
 * from Filament without a deploy.
 */
class PageSetting extends Model
{
    use HasFactory;

    public const MAX_TEAM_MEMBERS = 6;

    protected $guarded = [];

    protected $casts = [
        'expect_items'       => 'array',
        'home_about_bullets' => 'array',
        'home_owner_bullets' => 'array',
        'why_items'          => 'array',
        'testimonials'       => 'array',
    ];

    public static function current(): self
    {
        if (app()->bound('page-setting.current')) {
            return app('page-setting.current');
        }

        $row = static::firstOrCreate(['id' => 1], static::defaultAttributes());
        $row->ensureDefaultTeam();

        app()->instance('page-setting.current', $row);

        return $row;
    }

    public static function flushCache(): void
    {
        if (app()->bound('page-setting.current')) {
            app()->forgetInstance('page-setting.current');
        }
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class)->orderBy('sort_order')->orderBy('id');
    }

    public function publishedMembers(): HasMany
    {
        return $this->members()->published();
    }

    public function ensureDefaultTeam(): void
    {
        if ($this->members()->exists()) {
            return;
        }

        $this->members()->create([
            'title'        => 'Owner & Project Supervisor',
            'name'         => config('rdm.owner'),
            'is_published' => true,
            'sort_order'   => 1,
        ]);
    }

    public function seoTitle(): string
    {
        return $this->seo_title
            ?: 'About RDM Developments | Builder in Pretoria East';
    }

    public function metaDescription(): string
    {
        return $this->limitMeta($this->seo_meta_description ?: $this->about_intro
            ?: 'RDM Developments is a Pretoria East–based construction and renovation business owned and operated by Ruben Metcalfe. Every project is personally supervised.');
    }

    public function homeSeoTitle(): string
    {
        return $this->home_seo_title
            ?: 'Builder & Renovations Pretoria East | RDM Developments';
    }

    public function homeMetaDescription(): string
    {
        return $this->limitMeta($this->home_seo_meta_description
            ?: 'Owner-managed builder in Pretoria East. Building, bathroom renovations (including plumbing), tiling, waterproofing and painting — personally supervised by Ruben Metcalfe.');
    }

    public function contactSeoTitle(): string
    {
        return $this->contact_seo_title
            ?: 'Contact RDM Developments | Pretoria East Builder';
    }

    public function contactMetaDescription(): string
    {
        return $this->limitMeta($this->contact_seo_meta_description
            ?: 'Request a quote from RDM Developments for your building or renovation project in Pretoria East. Call, WhatsApp or complete the enquiry form.');
    }

    public function heroImageUrl(): ?string
    {
        return $this->about_hero_image
            ? asset('storage/' . $this->about_hero_image)
            : null;
    }

    public function homeHeroImageUrl(): ?string
    {
        if ($this->home_hero_image) {
            return asset('storage/' . $this->home_hero_image);
        }

        $heroFile = collect(['hero-home.webp', 'hero-home.jpg', 'hero-home.png'])
            ->first(fn (string $file) => file_exists(public_path('images/' . $file)));

        return $heroFile ? asset('images/' . $heroFile) : null;
    }

    protected function limitMeta(?string $raw): string
    {
        $raw = preg_replace('/#\S+/u', '', (string) $raw) ?? '';
        $raw = trim(preg_replace('/\s{2,}/u', ' ', $raw) ?? $raw);

        return Str::limit($raw, 155, '…');
    }

    /** @return array<int, array{title: string, text: string}> */
    public function expectItems(): array
    {
        return collect($this->expect_items ?? [])
            ->filter(fn ($item) => filled($item['title'] ?? null) || filled($item['text'] ?? null))
            ->values()
            ->all();
    }

    /** @return array<int, array{quote: string, name: string, suburb?: string, rating?: int}> */
    public function publishedTestimonials(): array
    {
        return collect($this->testimonials ?? [])
            ->filter(fn ($item) => filled($item['quote'] ?? null) && filled($item['name'] ?? null))
            ->map(fn (array $item) => [
                'quote'  => (string) $item['quote'],
                'name'   => (string) $item['name'],
                'suburb' => (string) ($item['suburb'] ?? ''),
                'rating' => (int) ($item['rating'] ?? 5),
            ])
            ->values()
            ->all();
    }

    /** @return array<int, string> */
    public function homeOwnerBullets(): array
    {
        return collect($this->home_owner_bullets ?? [])
            ->map(fn ($item) => is_string($item) ? $item : (string) ($item['text'] ?? ''))
            ->filter(fn (string $text) => filled($text))
            ->values()
            ->all();
    }

    /** @return array<int, array{icon: string, title: string, body: string}> */
    public function whyItems(): array
    {
        return collect($this->why_items ?? [])
            ->filter(fn ($item) => filled($item['title'] ?? null) || filled($item['body'] ?? null))
            ->map(fn (array $item) => [
                'icon'  => (string) ($item['icon'] ?? 'check-circle-2'),
                'title' => (string) ($item['title'] ?? ''),
                'body'  => (string) ($item['body'] ?? ''),
            ])
            ->values()
            ->all();
    }

    /** @return array<int, string> */
    public function homeAboutBullets(): array
    {
        return collect($this->home_about_bullets ?? [])
            ->map(function ($item) {
                if (is_string($item)) {
                    return $item;
                }

                return (string) ($item['text'] ?? '');
            })
            ->filter(fn (string $text) => filled($text))
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    public static function defaultAttributes(): array
    {
        $name  = config('rdm.name');
        $owner = config('rdm.owner');

        return [
            'about_eyebrow' => 'About RDM',
            'about_heading' => 'Personally supervised construction in Pretoria East',
            'about_intro'   => $name . ' is a Pretoria East–based construction and renovation business owned and operated by ' . $owner . '. As a small, owner-managed company, every project is personally supervised to ensure quality workmanship and reliable delivery.',
            'about_story_heading' => 'Why homeowners trust RDM Developments',
            'about_body' => '<p>Most issues on construction sites come down to one thing: nobody with skin in the game is actually watching the work. At ' . e($name) . ', that problem is solved by design. The owner, ' . e($owner) . ', is on-site and personally responsible for every project we take on.</p><p>We stay deliberately small. We don\'t have a sales team, we don\'t chase jobs all over Gauteng, and we don\'t juggle more projects than we can personally supervise. That\'s why clients in Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Silver Lakes and the rest of Pretoria East come back to us and refer their neighbours.</p>',
            'expect_heading' => 'What you can expect',
            'expect_items' => [
                ['title' => 'Honest quotes.', 'text' => 'Clear scope, clear pricing, no hidden extras.'],
                ['title' => 'Owner on-site.', 'text' => $owner . ' is your direct line for the whole project.'],
                ['title' => 'Tidy sites.', 'text' => 'We respect that you live there — we protect, cover and clean.'],
                ['title' => 'Quality tradesmen.', 'text' => 'Same plumbers and tilers, project after project.'],
                ['title' => 'Realistic timelines.', 'text' => 'Promises we can keep, not ones that look good on paper.'],
            ],
            'where_heading'  => 'Where we work',
            'where_we_work'  => 'We focus on Pretoria East suburbs including Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Silver Lakes, Olympus, Wapadrand, Elarduspark, Lynnwood and Menlo Park. Working in a tight geographic area means we can be responsive when issues come up on-site — because they always do.',
            'team_heading'   => 'Meet the team',
            'team_intro'     => 'The people who show up on site — faces and names, not a call centre.',
            'home_eyebrow' => 'Pretoria East · Gauteng',
            'home_heading' => 'Renovations & builds in Pretoria East, run personally by the owner',
            'home_intro'   => 'Clear quotes, clean sites and proper finishes. ' . $owner . ' personally quotes and runs every job — bathrooms, tiling, waterproofing, painting and building. You deal with him directly, start to finish.',
            'home_owner_quote' => 'Every project I quote, I run. No middlemen, no surprises — just good work, on time, on budget.',
            'home_owner_role'  => 'Owner · RDM Developments',
            'home_owner_bullets' => [
                ['text' => 'Owner on every project'],
                ['text' => 'Pretoria East focused'],
                ['text' => 'NHBRC-registered home builder'],
            ],
            'why_eyebrow' => 'Why RDM',
            'why_heading' => 'A small, focused team — and it shows in the finish',
            'why_intro'   => 'The difference between a good renovation and a frustrating one is almost always the person running it. Here\'s what working with us looks like.',
            'why_items' => [
                ['icon' => 'users', 'title' => 'Small, focused team', 'body' => 'We stay deliberately small so every project gets real attention — no call centre, no juggling twenty sites at once.'],
                ['icon' => 'shield-check', 'title' => 'Personally supervised', 'body' => $owner . ' is on-site and responsible for the work — the person who quoted your job is the person who runs it.'],
                ['icon' => 'message-circle', 'title' => 'Reliable communication', 'body' => 'Straight answers on WhatsApp or the phone. You always know where the project is and what happens next.'],
                ['icon' => 'sparkles', 'title' => 'Clean, well-finished', 'body' => 'Tidy sites, careful tradesmen, and the kind of finish you only get when someone is paying attention to the details.'],
            ],
            'projects_eyebrow' => 'Recent work',
            'projects_heading' => 'Featured projects',
            'projects_intro'   => 'A selection of the renovations and builds we\'ve completed across Pretoria East.',
            'services_eyebrow' => 'What we do',
            'services_heading' => 'Services across Pretoria East',
            'services_intro'   => 'From bathroom renovations and tiling to building, waterproofing and painting — every service is personally supervised by ' . $owner . ', from the first quote to the final clean-up.',
            'services_disclaimer' => 'We do not offer electrical work. Where a project requires it, the client appoints their own registered electrician.',
            'areas_eyebrow' => 'Where we work',
            'areas_heading' => 'Areas we serve in Pretoria East',
            'areas_intro'   => 'We focus on Pretoria East so we can be on-site fast and stay properly involved with every project — not stretched thin across the whole of Gauteng.',
            'home_seo_title' => 'Builder & Renovations Pretoria East | RDM Developments',
            'home_seo_meta_description' => 'Owner-managed builder in Pretoria East. Building, bathroom renovations (including plumbing), tiling, waterproofing and painting — personally supervised by ' . $owner . '.',
            'contact_eyebrow' => 'Get in touch',
            'contact_heading' => 'Request a Quote for Building or Renovation Work in Pretoria East',
            'contact_intro'   => 'Whether it\'s a bathroom renovation, a new build, or a custom project in between — ' . $owner . ' will personally talk you through what\'s possible, what it costs, and how long it takes. Honest answers, no pressure.',
            'contact_seo_title' => 'Contact RDM Developments | Pretoria East Builder',
            'contact_seo_meta_description' => 'Request a quote from RDM Developments for your building or renovation project in Pretoria East. Call, WhatsApp or complete the enquiry form.',
            'home_about_eyebrow' => 'About RDM',
            'home_about_heading' => 'Small, owner-managed, personally supervised',
            'home_about_intro'   => $name . ' is a Pretoria East–based construction and renovation business owned and operated by ' . $owner . '. We stay small on purpose — it\'s the only way to make sure every project is properly supervised and properly finished.',
            'home_about_bullets' => [
                ['text' => 'No sub-contracting of project management — ' . $owner . ' runs every job.'],
                ['text' => 'Focused on Pretoria East so we can be on-site fast.'],
                ['text' => 'Clear quotes, honest timelines, tidy sites.'],
            ],
            'testimonials_eyebrow' => 'What clients say',
            'testimonials_heading' => 'Trusted across Pretoria East',
            'testimonials_intro'   => 'Straight feedback from homeowners we\'ve worked with.',
            'testimonials' => [
                [
                    'quote'  => 'Ruben was on site, on time, and the finish was exactly what we agreed. No chasing, no surprises.',
                    'name'   => 'Homeowner',
                    'suburb' => 'Garsfontein',
                    'rating' => 5,
                ],
                [
                    'quote'  => 'Clear quote, tidy site, and the bathroom came out better than we expected. Would use RDM again.',
                    'name'   => 'Homeowner',
                    'suburb' => 'Faerie Glen',
                    'rating' => 5,
                ],
            ],
            'seo_title' => 'About RDM Developments | Builder in Pretoria East',
            'seo_meta_description' => $name . ' is a Pretoria East–based construction and renovation business owned and operated by ' . $owner . '. Every project is personally supervised.',
        ];
    }
}
