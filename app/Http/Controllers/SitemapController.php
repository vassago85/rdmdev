<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $services = Service::published()->ordered()->get(['slug', 'updated_at']);
        $projects = Project::published()->ordered()->get(['slug', 'updated_at']);

        $xml = view('sitemap', [
            'services' => $services,
            'projects' => $projects,
        ])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
