<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;

class HomeController extends Controller
{
    public function __invoke()
    {
        $services = Service::published()->ordered()->get();

        $featuredProjects = Project::published()
            ->with(['images'])
            ->featured()
            ->ordered()
            ->take(6)
            ->get();

        if ($featuredProjects->count() < 3) {
            $featuredProjects = Project::published()
                ->with(['images'])
                ->ordered()
                ->take(6)
                ->get();
        }

        return view('home', [
            'services'         => $services,
            'featuredProjects' => $featuredProjects,
            'pageTitle'        => 'Builder & Renovations Pretoria East | RDM Developments',
            'metaDescription'  => 'Owner-managed builder and renovation specialist in Pretoria East. Bathroom and home renovations, garage conversions, building contractors and more — personally supervised by Ruben Metcalfe.',
        ]);
    }
}
