<?php

namespace App\Filament\Resources;

use App\Enums\EnquiryStatus;
use App\Filament\Resources\EnquiryResource\Pages;
use App\Models\Enquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

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
            Forms\Components\Section::make('Contact details')
                ->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('phone')->required()->tel(),
                    Forms\Components\TextInput::make('email')->email(),
                    Forms\Components\TextInput::make('service_type')->label('Service'),
                    Forms\Components\TextInput::make('suburb'),
                    Forms\Components\TextInput::make('source')
                        ->helperText('Where the enquiry came from (e.g. home_cta).'),
                    Forms\Components\Textarea::make('message')->rows(6)->required()->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Lead management')
                ->description('Track where this lead is in your pipeline.')
                ->icon('heroicon-o-clipboard-document-check')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options(EnquiryStatus::class)
                        ->default(EnquiryStatus::New)
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('follow_up_at')
                        ->label('Next follow-up')
                        ->native(false)
                        ->helperText('When to chase this lead next.'),
                    Forms\Components\Textarea::make('notes')
                        ->label('Internal notes')
                        ->rows(4)
                        ->helperText('Private notes — e.g. "called, left voicemail 12 Jul". Not shown to the client.')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Enquiry')
                ->headerActions([
                    Infolists\Components\Actions\Action::make('whatsapp')
                        ->label('WhatsApp')
                        ->icon('heroicon-m-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn (Enquiry $record) => $record->whatsappUrl(
                            'Hi ' . $record->name . ', thanks for your enquiry to RDM Developments.'
                        ))
                        ->openUrlInNewTab()
                        ->visible(fn (Enquiry $record) => filled($record->phone)),
                    Infolists\Components\Actions\Action::make('call')
                        ->label('Call')
                        ->icon('heroicon-m-phone')
                        ->url(fn (Enquiry $record) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $record->phone))
                        ->visible(fn (Enquiry $record) => filled($record->phone)),
                    Infolists\Components\Actions\Action::make('email')
                        ->label('Email')
                        ->icon('heroicon-m-envelope')
                        ->color('gray')
                        ->url(fn (Enquiry $record) => $record->email ? 'mailto:' . $record->email : null)
                        ->visible(fn (Enquiry $record) => filled($record->email)),
                ])
                ->schema([
                    Infolists\Components\TextEntry::make('status')->badge(),
                    Infolists\Components\TextEntry::make('follow_up_at')
                        ->label('Next follow-up')->date('d M Y')->placeholder('—'),
                    Infolists\Components\TextEntry::make('name'),
                    Infolists\Components\TextEntry::make('phone')
                        ->url(fn ($state) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $state)),
                    Infolists\Components\TextEntry::make('email')
                        ->url(fn ($state) => $state ? 'mailto:' . $state : null)
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('service_type')->label('Service')->placeholder('—'),
                    Infolists\Components\TextEntry::make('suburb')->placeholder('—'),
                    Infolists\Components\TextEntry::make('source')->placeholder('—'),
                    Infolists\Components\TextEntry::make('message')->columnSpanFull()->markdown(),
                    Infolists\Components\TextEntry::make('notes')
                        ->label('Internal notes')->columnSpanFull()->placeholder('No notes yet.'),
                    Infolists\Components\TextEntry::make('created_at')->dateTime('d M Y H:i'),
                    Infolists\Components\TextEntry::make('read_at')->dateTime('d M Y H:i')->placeholder('Unread'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Tables\Columns\IconColumn::make('read_at')
                        ->label('')
                        ->icon(fn ($state) => $state ? 'heroicon-o-envelope-open' : 'heroicon-s-envelope')
                        ->color(fn ($state) => $state ? 'gray' : 'warning')
                        ->grow(false),
                    Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight(fn (Enquiry $record) => $record->read_at ? FontWeight::Medium : FontWeight::Bold),
                        Tables\Columns\TextColumn::make('service_type')
                            ->searchable()
                            ->color('gray')
                            ->icon('heroicon-m-wrench-screwdriver')
                            ->placeholder('No service specified'),
                    ]),
                    Stack::make([
                        Tables\Columns\TextColumn::make('phone')
                            ->searchable()
                            ->icon('heroicon-m-phone')
                            ->url(fn (Enquiry $record) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $record->phone)),
                        Tables\Columns\TextColumn::make('suburb')
                            ->searchable()
                            ->icon('heroicon-m-map-pin')
                            ->color('gray')
                            ->placeholder('—'),
                    ])->visibleFrom('sm'),
                    Stack::make([
                        Tables\Columns\TextColumn::make('status')
                            ->badge()
                            ->sortable(),
                        Tables\Columns\TextColumn::make('follow_up_at')
                            ->label('Follow-up')
                            ->date('d M Y')
                            ->icon('heroicon-m-calendar-days')
                            ->color(fn (Enquiry $record) => $record->follow_up_at && $record->follow_up_at->isPast() ? 'danger' : 'gray')
                            ->placeholder('—')
                            ->sortable(),
                    ])->alignEnd()->visibleFrom('md'),
                    Stack::make([
                        Tables\Columns\TextColumn::make('created_at')
                            ->since()
                            ->sortable()
                            ->color('gray')
                            ->tooltip(fn (Enquiry $record) => $record->created_at?->format('d M Y H:i')),
                    ])->alignEnd(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(EnquiryStatus::class)
                    ->multiple(),
                Tables\Filters\Filter::make('unread')
                    ->label('Unread only')
                    ->query(fn ($query) => $query->whereNull('read_at'))
                    ->toggle(),
                Tables\Filters\Filter::make('open')
                    ->label('Open leads')
                    ->query(fn ($query) => $query->open())
                    ->toggle(),
                Tables\Filters\Filter::make('follow_up_due')
                    ->label('Follow-up due')
                    ->query(fn ($query) => $query->whereNotNull('follow_up_at')->whereDate('follow_up_at', '<=', today()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->after(fn (Enquiry $record) => $record->markRead()),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('whatsapp')
                        ->label('WhatsApp')
                        ->icon('heroicon-m-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn (Enquiry $record) => $record->whatsappUrl(
                            'Hi ' . $record->name . ', thanks for your enquiry to RDM Developments.'
                        ))
                        ->openUrlInNewTab()
                        ->visible(fn (Enquiry $record) => filled($record->phone)),
                    Tables\Actions\Action::make('call')
                        ->label('Call')
                        ->icon('heroicon-m-phone')
                        ->url(fn (Enquiry $record) => 'tel:' . preg_replace('/[^0-9+]/', '', (string) $record->phone))
                        ->visible(fn (Enquiry $record) => filled($record->phone)),
                    Tables\Actions\Action::make('email')
                        ->label('Email')
                        ->icon('heroicon-m-envelope')
                        ->url(fn (Enquiry $record) => $record->email ? 'mailto:' . $record->email : null)
                        ->visible(fn (Enquiry $record) => filled($record->email)),
                ])->label('Contact')->icon('heroicon-m-chat-bubble-left-right')->button()->color('gray'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->isAdmin() ?? false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markRead')
                        ->label('Mark as read')
                        ->icon('heroicon-o-envelope-open')
                        ->action(fn ($records) => $records->each->markRead())
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('setStatus')
                        ->label('Set status')
                        ->icon('heroicon-o-flag')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(EnquiryStatus::class)
                                ->required()
                                ->native(false),
                        ])
                        ->action(fn ($records, array $data) => $records->each->update(['status' => $data['status']]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->isAdmin() ?? false),
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
