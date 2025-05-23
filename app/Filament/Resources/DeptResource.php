<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeptResource\Pages;
use App\Models\Dept;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DeptResource extends Resource
{
    protected static ?string $model = Dept::class;
    protected static ?string $navigationGroup = 'Department';
    protected static ?string $navigationLabel = 'Department';
    protected static ?string $pluralLabel = 'Departments';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('dept_id')
                ->label('ID'),
                Tables\Columns\TextColumn::make('dept_name')
                ->label('Department')
                ->searchable(),
                Tables\Columns\TextColumn::make('dept_code')
                ->label('Department code')
                ->searchable(),
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
        return in_array(Auth::user()?->role, [ 'admin', 'safety']);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->role !== 'employee';
    }
}
