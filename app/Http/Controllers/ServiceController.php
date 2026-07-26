<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Support\JsonLd;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::published()->ordered()->get();

        return view('services.index', [
            'services'        => $services,
            'pageTitle'       => 'Our Services in Pretoria East | RDM Developments',
            'metaDescription' => 'Building and renovation services across Pretoria East — building, bathroom renovations, tiling, waterproofing and painting. Personally supervised by Ruben Metcalfe.',
            'schemaExtra'     => [
                JsonLd::breadcrumbs([
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Services'],
                ]),
            ],
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_published, 404);

        $others = Service::published()->ordered()->where('id', '!=', $service->id)->take(4)->get();

        $schemaExtra = [
            JsonLd::service($service),
            JsonLd::breadcrumbs([
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Services', 'url' => route('services.index')],
                ['name' => $service->title],
            ]),
            JsonLd::faqPage($service->faqItems()),
        ];

        return view('services.show', [
            'service'         => $service,
            'others'          => $others,
            'pageTitle'       => $service->seoTitle(),
            'metaDescription' => $service->metaDescription(),
            'schemaExtra'     => $schemaExtra,
        ]);
    }
}
