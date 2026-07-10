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
        'facebook'  => null,
        'instagram' => null,
    ],

    'suburbs' => [
        'Garsfontein', 'Faerie Glen', 'Moreleta Park', 'Woodhill', 'Silver Lakes',
        'Olympus', 'Wapadrand', 'Elarduspark', 'Lynnwood', 'Menlo Park',
    ],

];
