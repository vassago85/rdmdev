<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Project Details')
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
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('project_type')
                        ->label('Project type')
                        ->options([
                            Project::TYPE_RENOVATION => 'Renovation (before & after)',
                            Project::TYPE_BUILD      => 'Completed build',
                        ])
                        ->default(Project::TYPE_RENOVATION)
                        ->required()
                        ->helperText('Renovations show a before / after gallery. Builds show a completed gallery.'),
                    Forms\Components\TextInput::make('category')
                        ->maxLength(80)
                        ->placeholder('Bathroom, Kitchen, Extension, Garage Conversion…'),
                    Forms\Components\TextInput::make('location')
                        ->label('Location / suburb')
                        ->maxLength(120)
                        ->placeholder('Garsfontein, Faerie Glen, Moreleta Park…'),
                    Forms\Components\DatePicker::make('completed_on')->label('Completed on'),
                    Forms\Components\RichEditor::make('description')
                        ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList', 'h2', 'h3', 'blockquote'])
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Featured Image')
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->disk('public')
                        ->directory('projects')
                        ->imageEditor()
                        ->imagePreviewHeight('220')
                        ->helperText('Shown on the projects grid and featured sections. Upload before/after gallery images in the “Images” tab after saving.'),
                ]),

            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('seo_title')->maxLength(191),
                    Forms\Components\Textarea::make('meta_description')->rows(2)->maxLength(500),
                ]),

            Forms\Components\Section::make('Settings')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_featured')->default(false),
                    Forms\Components\Toggle::make('is_published')->default(true),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->disk('public')
                    ->square()
                    ->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('project_type')
                    ->badge()
                    ->colors([
                        'primary' => 'renovation',
                        'success' => 'build',
                    ]),
                Tables\Columns\TextColumn::make('location')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('category')->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Feat.'),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Pub.'),
                Tables\Columns\TextColumn::make('completed_on')->date('M Y')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('project_type')->options([
                    Project::TYPE_RENOVATION => 'Renovation',
                    Project::TYPE_BUILD      => 'Build',
                ]),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit'   => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
