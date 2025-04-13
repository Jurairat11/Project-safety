<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProblemResource\Pages;
use App\Filament\Resources\ProblemResource\RelationManagers;
use App\Models\Problem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use App\Models\Employees;
use App\Models\Issue_report;

class ProblemResource extends Resource
{
    protected static ?string $model = Problem::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $navigationGroup = 'Problem Report';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('emp_id')
                            ->label('Reported by')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->placeholder("Select reported person"),

                Textarea::make('prob_desc')
                    ->label('Description')
                    ->required(),

                FileUpload::make('pic_before')
                    ->label('Picture')
                    ->image()
                    ->directory('form-attachments')
                    ->visibility('public'),

                TextInput::make('location')
                    ->label('Location')
                    ->required(),

                Select::make('linked_report_id')
                    ->label('Connect Issue Report')
                    ->options(Issue_report::all()->pluck('id', 'id'))
                    ->searchable()
                    ->nullable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'New',
                        'reported' => 'Reported',
                        'dismissed' => 'Dismissed',
                    ])
                    ->default('new')
                    ->disabled() // หากให้ระบบเปลี่ยนสถานะเอง
                    ->dehydrated(false), // ไม่ส่งกลับถ้า disabled
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('prob_id')
                    ->label('Problem ID'),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Reported by')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date reported')
                    ->dateTime('d/m/Y H:i'),
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
            'index' => Pages\ListProblems::route('/'),
            'create' => Pages\CreateProblem::route('/create'),
            'edit' => Pages\EditProblem::route('/{record}/edit'),
        ];
    }
}
