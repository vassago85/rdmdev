<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EnquiryResource;
use App\Models\Enquiry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentEnquiriesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent enquiries';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                EnquiryResource::getEloquentQuery()
                    ->latest('created_at')
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\IconColumn::make('read_at')
                    ->label('')
                    ->icon(fn ($state) => $state ? 'heroicon-o-envelope-open' : 'heroicon-s-envelope')
                    ->color(fn ($state) => $state ? 'gray' : 'warning'),
                Tables\Columns\TextColumn::make('name')
                    ->weight(fn (Enquiry $record) => $record->read_at ? null : 'bold'),
                Tables\Columns\TextColumn::make('phone')
                    ->url(fn (Enquiry $record) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $record->phone))
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('service_type')->label('Service')->toggleable(),
                Tables\Columns\TextColumn::make('suburb')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->tooltip(fn (Enquiry $record) => $record->created_at?->format('d M Y H:i')),
            ])
            ->recordUrl(fn (Enquiry $record) => EnquiryResource::getUrl('edit', ['record' => $record]))
            ->emptyStateHeading('No enquiries yet')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
