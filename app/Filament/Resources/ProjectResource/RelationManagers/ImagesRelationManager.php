<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $recordTitleAttribute = 'caption';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->options([
                    'before'  => 'Before (renovation)',
                    'after'   => 'After (renovation)',
                    'gallery' => 'Gallery (build / general)',
                ])
                ->default('gallery')
                ->required(),
            Forms\Components\FileUpload::make('path')
                ->image()
                ->disk('public')
                ->directory('projects/gallery')
                ->imageEditor()
                ->required(),
            Forms\Components\TextInput::make('caption')->maxLength(191),
            Forms\Components\TextInput::make('alt')->label('Alt text')->maxLength(191),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('caption')
            ->columns([
                Tables\Columns\ImageColumn::make('path')->disk('public')->square()->label(''),
                Tables\Columns\TextColumn::make('type')->badge()->colors([
                    'warning' => 'before',
                    'success' => 'after',
                    'primary' => 'gallery',
                ]),
                Tables\Columns\TextColumn::make('caption')->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    'before'  => 'Before',
                    'after'   => 'After',
                    'gallery' => 'Gallery',
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add image'),
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
}
