<?php

namespace App\Http\Controllers;

use App\Models\PageSetting;
use App\Support\JsonLd;

class PageController extends Controller
{
    public function about()
    {
        $about = PageSetting::current();
        $members = $about->publishedMembers()->get();
        $leadMember = $members->first();

        return view('about', [
            'about'           => $about,
            'members'         => $members,
            'leadMember'      => $leadMember,
            'pageTitle'       => $about->seoTitle(),
            'metaDescription' => $about->metaDescription(),
            'ogImage'         => $about->heroImageUrl()
                ?? $leadMember?->photoUrl(asset('images/ruben-metcalfe.jpg')),
            'schemaExtra'     => [
                JsonLd::aboutPage($about),
                ...$members->map(fn ($member) => JsonLd::person($member))->all(),
            ],
        ]);
    }

    public function contact()
    {
        $about = PageSetting::current();

        return view('contact', [
            'about'           => $about,
            'pageTitle'       => $about->contactSeoTitle(),
            'metaDescription' => $about->contactMetaDescription(),
            'schemaExtra'     => [
                JsonLd::contactPage(),
            ],
        ]);
    }
}
