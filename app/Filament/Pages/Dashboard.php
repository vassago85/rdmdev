<?php

namespace App\Filament\Pages;

use App\Filament\Resources\EnquiryResource;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Widgets\ContentDeskWidget;
use App\Filament\Widgets\EnquiryStatsWidget;
use App\Filament\Widgets\RecentEnquiriesWidget;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('editWebsite')
                ->label('Edit website')
                ->icon('heroicon-o-pencil-square')
                ->url(ManageAboutTeam::getUrl()),

            Action::make('newEnquiries')
                ->label('Enquiries')
                ->icon('heroicon-o-inbox-stack')
                ->color('warning')
                ->url(EnquiryResource::getUrl('index')),

            Action::make('newProject')
                ->label('New project')
                ->icon('heroicon-o-plus')
                ->url(ProjectResource::getUrl('create')),

            Action::make('newService')
                ->label('New service')
                ->icon('heroicon-o-plus')
                ->url(ServiceResource::getUrl('create')),
        ];
    }

    public function getWidgets(): array
    {
        return [
            ContentDeskWidget::class,
            AccountWidget::class,
            EnquiryStatsWidget::class,
            RecentEnquiriesWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
