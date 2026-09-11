<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageAboutTeam;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ServiceResource;
use Filament\Widgets\Widget;

class ContentDeskWidget extends Widget
{
    protected static string $view = 'filament.widgets.content-desk';

    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    /** @return array<int, array{label: string, description: string, url: string, icon: string}> */
    public function items(): array
    {
        return [
            [
                'label'       => 'Website content',
                'description' => 'Homepage, About, Team (6 faces), Testimonials, Contact, and SEO. Publish goes live immediately.',
                'url'         => ManageAboutTeam::getUrl(),
                'icon'        => 'heroicon-o-pencil-square',
            ],
            [
                'label'       => 'Services',
                'description' => 'Add or edit service pages, photos, FAQs, and service SEO.',
                'url'         => ServiceResource::getUrl('index'),
                'icon'        => 'heroicon-o-wrench-screwdriver',
            ],
            [
                'label'       => 'Projects',
                'description' => 'Portfolio entries, before/after galleries, featured flags.',
                'url'         => ProjectResource::getUrl('index'),
                'icon'        => 'heroicon-o-building-office-2',
            ],
        ];
    }
}
