<?php

namespace App\Support;

use App\Models\PageSetting;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Support\Str;

/**
 * Builds schema.org JSON-LD graphs for the public site.
 *
 * The business entity is emitted once per page with a stable @id
 * (config('rdm.schema_id')). Page-specific types reference that @id
 * instead of duplicating the GeneralContractor object.
 */
class JsonLd
{
    public static function business(): array
    {
        $sameAs = array_values(array_filter([
            config('rdm.social.google_business'),
            config('rdm.social.facebook'),
            config('rdm.social.instagram'),
        ]));

        $hours = collect(config('rdm.opening_hours', []))->map(fn (array $slot) => [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => $slot['days'],
            'opens'     => $slot['opens'],
            'closes'    => $slot['closes'],
        ])->values()->all();

        $entity = [
            '@type'       => 'GeneralContractor',
            '@id'         => config('rdm.schema_id'),
            'name'        => config('rdm.name'),
            'legalName'   => config('rdm.legal_name'),
            'image'       => asset('images/rdmdev-logo-960.png'),
            'url'         => rtrim(config('app.url'), '/') . '/',
            'telephone'   => config('rdm.phone_tel'),
            'email'       => config('rdm.email'),
            'priceRange'  => config('rdm.price_range'),
            'founder'     => config('rdm.owner'),
            'areaServed'  => [
                '@type' => 'Place',
                'name'  => 'Pretoria East, Gauteng, South Africa',
            ],
            'address' => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Pretoria East',
                'addressRegion'   => 'Gauteng',
                'addressCountry'  => 'ZA',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => config('rdm.geo.latitude'),
                'longitude' => config('rdm.geo.longitude'),
            ],
            'makesOffer' => Service::published()->ordered()->get(['title', 'slug'])->map(fn (Service $s) => [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name'  => $s->title,
                    'url'   => route('services.show', $s->slug),
                ],
            ])->values()->all(),
        ];

        if (! empty($hours)) {
            $entity['openingHoursSpecification'] = $hours;
        }

        if (! empty($sameAs)) {
            $entity['sameAs'] = $sameAs;
        }

        // AggregateRating — emitted only when real Google numbers are configured.
        // Never fabricate a rating: Google penalises invented review markup.
        $rating = config('rdm.google.rating');
        $count  = config('rdm.google.review_count');
        if (filled($rating) && filled($count)) {
            $entity['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (string) $rating,
                'reviewCount' => (string) $count,
                'bestRating'  => '5',
                'worstRating' => '1',
            ];
        }

        return $entity;
    }

    public static function service(Service $service): array
    {
        return [
            '@type'       => 'Service',
            '@id'         => route('services.show', $service->slug) . '#service',
            'name'        => $service->title,
            'description' => Str::limit(strip_tags($service->excerpt ?: $service->description ?: ''), 300),
            'url'         => route('services.show', $service->slug),
            'provider'    => ['@id' => config('rdm.schema_id')],
            'areaServed'  => [
                '@type' => 'Place',
                'name'  => 'Pretoria East, Gauteng, South Africa',
            ],
            'serviceType' => $service->title,
        ];
    }

    public static function aboutPage(PageSetting $page): array
    {
        return [
            '@type'      => 'AboutPage',
            '@id'        => route('about') . '#about',
            'name'       => $page->seoTitle(),
            'description'=> $page->metaDescription(),
            'url'        => route('about'),
            'mainEntity' => ['@id' => config('rdm.schema_id')],
            'about'      => ['@id' => config('rdm.schema_id')],
        ];
    }

    public static function person(TeamMember $member): array
    {
        $person = [
            '@type'    => 'Person',
            '@id'      => route('about') . '#person-' . $member->id,
            'name'     => $member->name,
            'jobTitle' => $member->title,
            'worksFor' => ['@id' => config('rdm.schema_id')],
            'url'      => route('about') . '#team',
        ];

        if ($photo = $member->photoUrl()) {
            $person['image'] = $photo;
        }

        return $person;
    }

    public static function contactPage(): array
    {
        return [
            '@type'      => 'ContactPage',
            '@id'        => route('contact') . '#contact',
            'name'       => 'Contact ' . config('rdm.name'),
            'url'        => route('contact'),
            'mainEntity' => ['@id' => config('rdm.schema_id')],
            'about'      => ['@id' => config('rdm.schema_id')],
        ];
    }

    public static function project(Project $project): array
    {
        $images = collect([
            $project->featuredImageUrl(),
            ...$project->images->map(fn ($img) => $img->url())->all(),
        ])->filter()->unique()->take(8)->values()->all();

        $work = [
            '@type'       => 'CreativeWork',
            '@id'         => route('projects.show', $project->slug) . '#project',
            'name'        => $project->displayHeading(),
            'description' => $project->metaDescription(),
            'url'         => route('projects.show', $project->slug),
            'creator'     => ['@id' => config('rdm.schema_id')],
            'contributor' => ['@id' => config('rdm.schema_id')],
        ];

        if (! empty($images)) {
            $work['image'] = $images;
        }

        if ($project->location) {
            $work['contentLocation'] = [
                '@type' => 'Place',
                'name'  => $project->location . ', Pretoria East, Gauteng',
            ];
        }

        if ($project->completed_on) {
            $work['dateCreated'] = $project->completed_on->toDateString();
        }

        return $work;
    }

    public static function breadcrumbs(array $crumbs): array
    {
        return [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => collect($crumbs)->values()->map(fn (array $crumb, int $i) => array_filter([
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['name'],
                'item'     => $crumb['url'] ?? null,
            ]))->all(),
        ];
    }

    public static function faqPage(array $items): ?array
    {
        $entities = collect($items)
            ->filter(fn ($item) => filled($item['question'] ?? null) && filled($item['answer'] ?? null))
            ->map(fn (array $item) => [
                '@type' => 'Question',
                'name'  => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => strip_tags($item['answer']),
                ],
            ])
            ->values()
            ->all();

        if (empty($entities)) {
            return null;
        }

        return [
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    /**
     * Encode a @graph of schema entities for embedding in a <script> tag.
     *
     * @param  array<int, array|null>  $entities
     */
    public static function graph(array $entities): string
    {
        $graph = array_values(array_filter($entities));

        return json_encode([
            '@context' => 'https://schema.org',
            '@graph'   => $graph,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    }
}
