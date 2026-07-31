<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Service Details')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(191)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set, ?string $operation) {
                            if ($operation === 'create' && filled($state)) {
                                $set('slug', Str::slug($state));
                            }
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true)
                        ->helperText('Used in the URL, e.g. /services/bathroom-renovations-pretoria-east'),
                    Forms\Components\TextInput::make('tagline')
                        ->maxLength(191)
                        ->helperText('Short one-liner shown under the title.'),
                    Forms\Components\Select::make('icon')
                        ->options([
                            'hammer'       => 'Hammer — building / construction',
                            'bath'         => 'Bath — bathrooms',
                            'grid'         => 'Grid — tiling',
                            'droplet'      => 'Droplet — waterproofing',
                            'paintbrush'   => 'Paintbrush — painting',
                            'house'        => 'House — renovations',
                            'layers'       => 'Layers — custom / other',
                            'construction' => 'Construction',
                            'file-text'    => 'Document',
                        ])
                        ->searchable()
                        ->native(false)
                        ->placeholder('Choose an icon')
                        ->helperText('Shown on the service card. Pick from the supported set to avoid broken icons.'),
                    Forms\Components\Textarea::make('excerpt')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Summary used on service cards.'),
                    Forms\Components\RichEditor::make('description')
                        ->toolbarButtons([
                            'bold', 'italic', 'link', 'bulletList', 'orderedList',
                            'h2', 'h3', 'blockquote', 'undo', 'redo',
                        ])
                        ->helperText('Use H2 for section headings (e.g. “What’s included…”) so the page outline is H1 → H2 → H3.')
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Media')
                ->schema([
                    Forms\Components\FileUpload::make('hero_image')
                        ->image()
                        ->disk('public')
                        ->directory('services')
                        ->imageEditor()
                        ->imagePreviewHeight('200'),
                ]),

            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('seo_title')
                        ->maxLength(191)
                        ->helperText('Recommended 50–60 characters.'),
                    Forms\Components\Textarea::make('meta_description')
                        ->rows(2)
                        ->maxLength(160)
                        ->helperText('Recommended 140–160 characters. Hashtags are stripped on output.'),
                ]),

            Forms\Components\Section::make('FAQ (optional)')
                ->description('Shown below the service description. Also emitted as FAQPage structured data when populated.')
                ->schema([
                    Forms\Components\Repeater::make('faq')
                        ->label('Questions')
                        ->schema([
                            Forms\Components\TextInput::make('question')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('answer')
                                ->required()
                                ->rows(3)
                                ->maxLength(2000),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->reorderable()
                        ->columnSpanFull(),
                ])
                ->collapsed(),

            Forms\Components\Section::make('Settings')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_published')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->isAdmin() ?? false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->isAdmin() ?? false),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
