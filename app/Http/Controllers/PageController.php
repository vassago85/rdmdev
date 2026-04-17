<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('about', [
            'pageTitle'       => 'About RDM Developments | Builder in Pretoria East',
            'metaDescription' => 'RDM Developments is a Pretoria East–based construction and renovation business owned and operated by Ruben Metcalfe. Every project is personally supervised.',
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'pageTitle'       => 'Contact RDM Developments | Pretoria East Builder',
            'metaDescription' => 'Request a quote from RDM Developments for your building or renovation project in Pretoria East. Call, WhatsApp or complete the enquiry form.',
        ]);
    }
}
