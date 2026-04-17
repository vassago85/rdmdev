<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnquiryResource\Pages;
use App\Models\Enquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Enquiry::unread()->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('phone')->required(),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('service_type'),
            Forms\Components\TextInput::make('suburb'),
            Forms\Components\Textarea::make('message')->rows(6)->required()->columnSpanFull(),
            Forms\Components\TextInput::make('source'),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Enquiry')
                ->schema([
                    Infolists\Components\TextEntry::make('name'),
                    Infolists\Components\TextEntry::make('phone')
                        ->url(fn ($state) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $state)),
                    Infolists\Components\TextEntry::make('email')
                        ->url(fn ($state) => $state ? 'mailto:' . $state : null),
                    Infolists\Components\TextEntry::make('service_type')->label('Service'),
                    Infolists\Components\TextEntry::make('suburb'),
                    Infolists\Components\TextEntry::make('source'),
                    Infolists\Components\TextEntry::make('message')->columnSpanFull()->markdown(),
                    Infolists\Components\TextEntry::make('created_at')->dateTime('d M Y H:i'),
                    Infolists\Components\TextEntry::make('read_at')->dateTime('d M Y H:i')->placeholder('Unread'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('read_at')
                    ->label('')
                    ->icon(fn ($state) => $state ? 'heroicon-o-envelope-open' : 'heroicon-s-envelope')
                    ->color(fn ($state) => $state ? 'gray' : 'warning'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->searchable()
                    ->url(fn ($record) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $record->phone)),
                Tables\Columns\TextColumn::make('service_type')->label('Service')->toggleable(),
                Tables\Columns\TextColumn::make('suburb')->toggleable(),
                Tables\Columns\TextColumn::make('message')->limit(60)->wrap()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('unread')
                    ->label('Unread only')
                    ->query(fn ($query) => $query->whereNull('read_at'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->after(fn (Enquiry $record) => $record->markRead()),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markRead')
                        ->label('Mark as read')
                        ->icon('heroicon-o-envelope-open')
                        ->action(fn ($records) => $records->each->markRead()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEnquiries::route('/'),
            'create' => Pages\CreateEnquiry::route('/create'),
            'edit'   => Pages\EditEnquiry::route('/{record}/edit'),
        ];
    }
}
