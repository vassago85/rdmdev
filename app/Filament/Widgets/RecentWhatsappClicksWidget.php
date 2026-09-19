<?php

namespace App\Filament\Widgets;

use App\Models\WhatsappClick;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentWhatsappClicksWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent WhatsApp clicks';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                WhatsappClick::query()
                    ->latest('created_at')
                    ->limit(8)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('placement')
                    ->label('Button')
                    ->formatStateUsing(fn (WhatsappClick $record) => $record->placementLabel()),
                Tables\Columns\TextColumn::make('path')
                    ->label('Page')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Clicked')
                    ->since()
                    ->tooltip(fn (WhatsappClick $record) => $record->created_at?->format('d M Y H:i')),
            ])
            ->emptyStateHeading('No WhatsApp clicks yet')
            ->emptyStateDescription('Clicks on any WhatsApp button on the public site will show up here.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }
}
