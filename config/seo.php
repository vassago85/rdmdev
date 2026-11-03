<?php

return [

    /*
    |--------------------------------------------------------------------------
    | sameAs
    |--------------------------------------------------------------------------
    |
    | Absolute profile URLs for schema.org sameAs on the GeneralContractor
    | entity: Google Business Profile, Facebook, Instagram. Leave this empty
    | until the real profiles exist. Do not put placeholder text here.
    |
    */
    'same_as' => [
        // 'https://...',
    ],

    /*
    |--------------------------------------------------------------------------
    | hasMap
    |--------------------------------------------------------------------------
    |
    | Google Maps URL for the business pin (schema.org hasMap). Leave null
    | until the Google Business Profile maps link is known.
    |
    */
    'has_map' => null,

    /*
    |--------------------------------------------------------------------------
    | Fallback lastmod
    |--------------------------------------------------------------------------
    |
    | Used only when a static page has no related content timestamp.
    | Sitemap lastmod must never be "now()".
    |
    */
    'content_updated_at' => '2026-04-17T00:00:00+02:00',

];
