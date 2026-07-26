<?php

namespace App\Http\Controllers;

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

        $xml = view('sitemap', [
            'services'           => $services,
            'projects'           => $projects,
            'homeUrl'            => rtrim(config('app.url'), '/') . '/',
            'servicesLastmod'    => optional($services->max('updated_at'))?->toAtomString(),
            'projectsLastmod'    => optional($projects->max('updated_at'))?->toAtomString(),
            'aboutLastmod'       => $this->viewLastmod('about.blade.php'),
            'contactLastmod'     => $this->viewLastmod('contact.blade.php'),
        ])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    protected function viewLastmod(string $relativeView): string
    {
        $path = resource_path('views/' . $relativeView);

        return Carbon::createFromTimestamp(file_exists($path) ? filemtime($path) : time())
            ->toAtomString();
    }
}
