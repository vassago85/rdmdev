<?php

namespace App\Http\Controllers;

use App\Models\PageSetting;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $services = Service::published()->ordered()->get(['slug', 'updated_at']);
        $projects = Project::published()->ordered()->get(['slug', 'updated_at']);
        $pageUpdated = PageSetting::query()->value('updated_at');
        $servicesUpdated = $services->max('updated_at');
        $projectsUpdated = $projects->max('updated_at');

        $urls = [
            [
                'loc' => rtrim((string) config('app.url'), '/') . '/',
                'lastmod' => $this->latest($servicesUpdated, $projectsUpdated, $pageUpdated),
            ],
            [
                'loc' => route('services.index'),
                'lastmod' => $this->latest($servicesUpdated),
            ],
            [
                'loc' => route('projects.index'),
                'lastmod' => $this->latest($projectsUpdated),
            ],
            [
                'loc' => route('about'),
                'lastmod' => $this->latest($pageUpdated),
            ],
            [
                'loc' => route('contact'),
                'lastmod' => $this->latest($pageUpdated),
            ],
        ];

        foreach ($services as $service) {
            $urls[] = [
                'loc' => route('services.show', $service->slug),
                'lastmod' => $service->updated_at?->toAtomString(),
            ];
        }

        foreach ($projects as $project) {
            $urls[] = [
                'loc' => route('projects.show', $project->slug),
                'lastmod' => $project->updated_at?->toAtomString(),
            ];
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    protected function latest(mixed ...$dates): string
    {
        $latest = collect($dates)
            ->filter()
            ->map(fn ($date) => Carbon::parse($date))
            ->sort()
            ->last();

        return ($latest ?: Carbon::parse((string) config('seo.content_updated_at')))->toAtomString();
    }
}
