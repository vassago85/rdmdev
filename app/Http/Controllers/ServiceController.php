<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::published()->ordered()->get();

        return view('services.index', [
            'services'        => $services,
            'pageTitle'       => 'Our Services in Pretoria East | RDM Developments',
            'metaDescription' => 'Building and renovation services across Pretoria East — bathrooms, home renovations, garage conversions, building contractors and painting. Personally supervised by Ruben Metcalfe.',
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_published, 404);

        $others = Service::published()->ordered()->where('id', '!=', $service->id)->take(4)->get();

        return view('services.show', [
            'service'         => $service,
            'others'          => $others,
            'pageTitle'       => $service->seoTitle(),
            'metaDescription' => $service->metaDescription(),
        ]);
    }
}
