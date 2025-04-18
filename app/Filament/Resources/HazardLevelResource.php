<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HazardLevelResource\Pages;
use App\Filament\Resources\HazardLevelResource\RelationManagers;
use App\Models\HazardLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HazardLevelResource extends Resource
{
    protected static ?string $model = HazardLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    protected static ?string $navigationGroup = 'Issue Report';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Level')
                ->label('Hazard level'),
                Forms\Components\TextArea::make('desc')
                ->label('Description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hazard_level_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('Level')
                    ->label('Hazard level'),
                Tables\Columns\TextColumn::make('desc')
                    ->label('Description'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHazardLevels::route('/'),
            'create' => Pages\CreateHazardLevel::route('/create'),
            'edit' => Pages\EditHazardLevel::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role !== 'employee';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return in_array (auth()->user()?->role, ['admin','safety']);
    }
}
