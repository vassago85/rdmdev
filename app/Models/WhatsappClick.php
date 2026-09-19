<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WhatsappClick extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const PLACEMENTS = [
        'hero'     => 'Homepage hero',
        'header'   => 'Mobile menu',
        'footer'   => 'Footer',
        'floating' => 'Floating button',
        'sticky'   => 'Mobile bar',
        'cta'      => 'Bottom CTA',
        'service'  => 'Service page',
        'project'  => 'Project page',
        'about'    => 'About page',
        'contact'  => 'Contact page',
        'unknown'  => 'Other',
    ];

    public function placementLabel(): string
    {
        return self::PLACEMENTS[$this->placement] ?? Str::headline((string) $this->placement);
    }
}
