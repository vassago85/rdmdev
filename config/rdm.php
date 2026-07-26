<?php

return [

    'name'        => 'RDM Developments',
    'tagline'     => 'Building & Renovation Specialists in Pretoria East',
    'owner'       => env('RDM_OWNER', 'Ruben Metcalfe'),
    'location'    => env('RDM_LOCATION', 'Pretoria East, Gauteng'),

    'legal_name'            => 'RDM Developments (Pty) Ltd',
    'registration_number'  => '2025/525638/07',
    // TODO: supply NHBRC home-builder registration number when available.
    'nhbrc_number'          => env('RDM_NHBRC_NUMBER'),

    'phone'       => env('RDM_PHONE', '072 972 9393'),
    'phone_tel'   => env('RDM_PHONE_TEL', '+27729729393'),
    'whatsapp'    => env('RDM_WHATSAPP', '27729729393'),
    'email'       => env('RDM_EMAIL', 'ruben@rdmdev.co.za'),

    'enquiry_to'  => env('RDM_ENQUIRY_TO', env('RDM_EMAIL', 'ruben@rdmdev.co.za')),

    'whatsapp_greeting' => 'Hi Ruben, I\'d like to request a quote from RDM Developments.',

    'social' => [
        // Absolute profile URLs for schema.org sameAs. Leave null until live.
        'google_business' => env('RDM_GBP_URL'),       // TODO: Google Business Profile URL
        'facebook'        => env('RDM_FACEBOOK_URL'),  // TODO: Facebook page URL
        'instagram'       => env('RDM_INSTAGRAM_URL'), // TODO: Instagram profile URL
    ],

    // LocalBusiness geo — Pretoria East centroid placeholders. Replace with
    // the pin from your Google Business Profile before claiming rich results.
    'geo' => [
        'latitude'  => env('RDM_LATITUDE', '-25.8150'),  // TODO: exact lat
        'longitude' => env('RDM_LONGITUDE', '28.3000'), // TODO: exact lng
    ],

    // Schema.org priceRange hint (e.g. "$$", "R5 000 – R250 000").
    'price_range' => env('RDM_PRICE_RANGE', '$$'),

    // Opening hours for OpeningHoursSpecification. Edit as needed.
    'opening_hours' => [
        ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '07:00', 'closes' => '17:00'],
        ['days' => ['Saturday'], 'opens' => '08:00', 'closes' => '13:00'],
        // Sunday closed — omit rather than listing closed days.
    ],

    // Stable schema.org @id for the business entity. Other JSON-LD blocks
    // should reference this rather than duplicating the full object.
    'schema_id' => 'https://rdmdev.co.za/#business',

    'suburbs' => [
        'Garsfontein', 'Faerie Glen', 'Moreleta Park', 'Woodhill', 'Silver Lakes',
        'Olympus', 'Wapadrand', 'Elarduspark', 'Lynnwood', 'Menlo Park',
    ],

];
