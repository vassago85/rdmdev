<?php

return [

    'name'        => 'RDM Developments',
    'tagline'     => 'Building & Renovation Specialists in Pretoria East',
    'owner'       => env('RDM_OWNER', 'Ruben Metcalfe'),
    'location'    => env('RDM_LOCATION', 'Pretoria East, Gauteng'),

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
