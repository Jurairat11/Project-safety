<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Employees;
use App\Models\Dept;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employees::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('emp_id')
                    ->label('Employee code')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('emp_name')
                    ->label('First name')
                    ->required(),
                Forms\Components\TextInput::make('lastname')
                    ->label('Last name')
                    ->required(),
                Forms\Components\Select::make('dept_id')
                    ->label('Department')
                    ->relationship('dept','dept_name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emp_id')
                    ->label('Employee code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('emp_name')
                    ->label('First name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Last name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dept.dept_name')
                    ->label('Department'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dept_id')
                ->label('Department')
                ->options(
                    Dept::all()->pluck('dept_name', 'dept_id') // หรือ 'id' แล้วแต่ชื่อ field
                )
                ->searchable()
                ->visible(fn () => Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'safety')
                || Auth::user()->role === 'employee')
                ->default(null),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                /*->visible(fn ($record) => $record->emp_id === auth()->user()?->emp_id), //เฉพาะเจ้าของ*/
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployees::route('/create'),
            'edit' => Pages\EditEmployees::route('/{record}/edit'),
        ];
    }

        public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->role === 'safety') {
            return $query->whereHas('user', fn ($q) =>
                $q->where('role', '!=', 'admin')
            );
        }

        return $query->where('dept_id', Auth::user()?->dept_id);

    }
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()?->role, [ 'employee','safety','department']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return  in_array(Auth::user()?->role, [ 'employee','safety','department']);
    }

    public static function canCreate(): bool
    {
        return false; // ปิดการสร้างพนักงานใหม่
    }

}



