<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'safety' => 'Safety Officer',
                        'department' => 'Responsible Department',
                        'employee' => 'Employee',
                    ])
                    ->required()
                    ->visible(fn () => \Illuminate\Support\Facades\Auth::user()?->role === 'admin') //  เฉพาะ admin เท่านั้นที่เห็น
                    ->disabled(fn () =>\Illuminate\Support\Facades\Auth::user()?->role !== 'admin'), // ป้องกัน user เปลี่ยน role ตัวเอง

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('emp_id')->label('Employee ID'),
                TextColumn::make('emp_name')->label('First Name'),
                TextColumn::make('lastname')->label('Last Name'),
                TextColumn::make('email'),
                BadgeColumn::make('role')
                    ->colors([
                        'admin' => 'red',
                        'safety' => 'green',
                        'department' => 'blue',
                        'employee' => 'gray',
                    ])
                    ->label('Role'),
            ])
            ->filters([
                SelectFilter::make('role')
                ->label('Filter by Role')
                ->options([
                    'admin' => 'Admin',
                    'safety' => 'Safety Officer',
                    'department' => 'Department',
                    'employee' => 'Employee',
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return \Illuminate\Support\Facades\Auth::user()?->role === 'admin';
    }


}
