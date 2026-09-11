<?php

namespace App\Http\Controllers;

use App\Models\PageSetting;
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

        $about = PageSetting::current();
        $leadMember = $about->publishedMembers()->first();

        return view('home', [
            'services'         => $services,
            'featuredProjects' => $featuredProjects,
            'about'            => $about,
            'leadMember'       => $leadMember,
            'pageTitle'        => $about->homeSeoTitle(),
            'metaDescription'  => $about->homeMetaDescription(),
        ]);
    }
}
