<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssueResponsesResource\Pages;
use App\Filament\Resources\IssueResponsesResource\RelationManagers;
use App\Models\Issue_responses; // Ensure this model exists in the specified namespace
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Issue_report;

class IssueResponsesResource extends Resource
{
    protected static ?string $model = Issue_responses::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Issue Responses')
                ->schema([
                    Forms\Components\Select::make('report_id')
                        ->label('Report ID')
                        ->relationship('issue_report','report_id')
                        ->options(function () {
                            return Issue_report::all()->pluck('report_id', 'report_id');
                        })
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {

                            $issue_report = Issue_report::find($state);
                            if ( $issue_report) {

                                $set('emp_id', $issue_report->emp_id);
                            }
                        })
                        ->required(),

                    Forms\Components\TextInput::make('emp_id')
                        ->label('Reporting employee'),

                    Forms\Components\Textarea::make('cause')
                        ->label('Cause')
                        ->required()
                        ->columnSpan(2),

                Forms\Components\Section::make('Issue Solving')
                ->schema([

                        Forms\Components\FileUpload::make('img_after')
                        ->label('Picture After')
                        ->directory('form-attachments')
                        ->visibility('public')
                        ->required()
                        ->columnSpan(2),

                        Forms\Components\Textarea::make('temporary_act')
                            ->label('Temporary Action')
                            ->default('-'),

                        Forms\Components\Textarea::make('permanent_act')
                            ->label('Permanent Action')
                            ->default('-'),

                        Forms\Components\DatePicker::make('temp_due_date')
                            ->label('Due date')
                            ->displayFormat('d/m/y'),

                        Forms\Components\DatePicker::make('perm_due_date')
                            ->label('Due date')
                            ->displayFormat('d/m/y'),

                        Forms\Components\Select::make('temp_responsible')
                            ->label('Responsible Person')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->placeholder("Select responsible person"),

                        Forms\Components\Select::make('perm_responsible')
                            ->label('Responsible Person')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->placeholder("Select responsible person"),

                        Forms\Components\Textarea::make('preventive_act')
                            ->label('Preventive Action')
                            ->required()
                            ->columnSpan(2),

                        /*Forms\Components\Select::make('emp_id')
                            ->label('Created by')
                            ->options(function(){
                                return \App\Models\Employees::where('emp_id',1)->pluck('emp_id');
                            }),*/

                        Forms\Components\Select::make('created_by')
                            ->label('Created by')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->required()
                            ->placeholder("Select create person")

                    ]) ->columns(2)
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('response_id')
                ->label('Response_id'),
                Tables\Columns\TextColumn::make('report_id')
                ->label('Report_id'),
                Tables\Columns\TextColumn::make('cause')
                ->label('Cause'),
                Tables\Columns\ImageColumn::make('img_after')
                ->label('Picture after'),
                Tables\Columns\TextColumn::make('created_by')
                ->label('Created by')
                ->searchable()
                ->sortable(),

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
            'index' => Pages\ListIssueResponses::route('/'),
            'create' => Pages\CreateIssueResponses::route('/create'),
            'edit' => Pages\EditIssueResponses::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role !== 'employee';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role !== 'employee';
    }



}
