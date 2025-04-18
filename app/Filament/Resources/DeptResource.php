<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeptResource\Pages;
use App\Filament\Resources\DeptResource\RelationManagers;
use App\Models\Dept;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeptResource extends Resource
{
    protected static ?string $model = Dept::class;
    protected static ?string $navigationGroup = 'Department';
    protected static ?string $navigationLabel = 'Department';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('dept_name')
                    ->label('Department')
                    ->required(),
                Forms\Components\TextInput::make('dept_code')
                    ->label('Department code')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dept_id')->label('ID'),
                Tables\Columns\TextColumn::make('dept_name')->label('Department')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('dept_code')->label('Department code')->sortable()->searchable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepts::route('/'),
            'create' => Pages\CreateDept::route('/create'),
            'edit' => Pages\EditDept::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, [ 'admin', 'safety']);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role !== 'employee';
    }
}
