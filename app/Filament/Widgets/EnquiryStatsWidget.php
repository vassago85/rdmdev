<?php

namespace App\Filament\Widgets;

use App\Models\Enquiry;
use App\Models\Project;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class EnquiryStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();

        // This week vs last week (Mon–Sun) for a simple trend.
        $thisWeekStart = $now->copy()->startOfWeek();
        $lastWeekStart = $thisWeekStart->copy()->subWeek();

        $thisWeek = Enquiry::where('created_at', '>=', $thisWeekStart)->count();
        $lastWeek = Enquiry::whereBetween('created_at', [$lastWeekStart, $thisWeekStart])->count();

        $delta = $thisWeek - $lastWeek;
        $trendIcon = $delta > 0 ? 'heroicon-m-arrow-trending-up'
            : ($delta < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus');
        $trendColor = $delta > 0 ? 'success' : ($delta < 0 ? 'danger' : 'gray');
        $trendLabel = $delta === 0
            ? 'Same as last week'
            : sprintf('%s%d vs last week', $delta > 0 ? '+' : '', $delta);

        $unread = Enquiry::unread()->count();
        $thisMonth = Enquiry::where('created_at', '>=', $now->copy()->startOfMonth())->count();

        $servicesLive = Service::where('is_published', true)->count();
        $servicesDraft = Service::where('is_published', false)->count();
        $projectsLive = Project::where('is_published', true)->count();
        $projectsDraft = Project::where('is_published', false)->count();

        return [
            Stat::make('New enquiries this week', $thisWeek)
                ->description($trendLabel)
                ->descriptionIcon($trendIcon)
                ->color($trendColor),

            Stat::make('Unactioned enquiries', $unread)
                ->description($unread > 0 ? 'Need a response' : 'All caught up')
                ->descriptionIcon($unread > 0 ? 'heroicon-m-envelope' : 'heroicon-m-check-circle')
                ->color($unread > 0 ? 'warning' : 'success'),

            Stat::make('Enquiries this month', $thisMonth)
                ->description('Since ' . $now->copy()->startOfMonth()->format('j M'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Services', "{$servicesLive} live")
                ->description("{$servicesDraft} draft")
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color($servicesDraft > 0 ? 'gray' : 'success'),

            Stat::make('Projects', "{$projectsLive} live")
                ->description("{$projectsDraft} draft")
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color($projectsDraft > 0 ? 'gray' : 'success'),
        ];
    }
}
