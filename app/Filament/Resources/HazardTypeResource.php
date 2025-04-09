<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HazardTypeResource\Pages;
use App\Filament\Resources\HazardTypeResource\RelationManagers;
use App\Models\HazardType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HazardTypeResource extends Resource
{
    protected static ?string $model = HazardType::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    protected static ?string $navigationGroup = 'Problem Report';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Desc')
                ->label('Hazard type')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hazard_type_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('Desc')
                    ->label('Hazard type'),
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
            'index' => Pages\ListHazardTypes::route('/'),
            'create' => Pages\CreateHazardType::route('/create'),
            'edit' => Pages\EditHazardType::route('/{record}/edit'),
        ];
    }
}
