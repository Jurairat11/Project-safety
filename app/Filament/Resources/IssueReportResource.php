<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssueReportResource\Pages;
use App\Filament\Resources\IssueReportResource\RelationManagers;
use App\Models\Issue_report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Problem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IssueReportResource extends Resource
{
    protected static ?string $model = Issue_report::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('prob_id')
                ->label('P-CAR Details')
                ->schema([
                    Forms\Components\Select::make('prob_id')
                        ->label('Problem ID')
                        ->relationship('problem','prob_id')
                        ->options(function () {
                            return Problem::all()->pluck('prob_id', 'prob_id');
                        })
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {

                            $problem = Problem::find($state);
                            if ($problem) {

                                $set('prob_desc', $problem->prob_desc);
                                $set('emp_id', $problem->emp_id);
                            }
                        })
                        ->required(),

                    Forms\Components\TextInput::make('emp_id')
                        ->label('Reporting employee'),



                    Forms\Components\Textarea::make('prob_desc')
                        ->label('Description')
                        ->columnSpan(2),

                    Forms\Components\Fieldset::make('Issue Report')
                    ->schema([
                        Forms\Components\Select::make('hazard_level_id')
                        ->label('Hazard Level')
                        ->relationship('hazardLevel','Level')
                        ->required(),

                        Forms\Components\Select::make('hazard_type_id')
                        ->label('Hazard Type')
                        ->relationship('hazardType', 'Desc')
                        ->required(),

                        Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'reported' => 'Reported',
                            'in_progress' => 'In progress',
                            'resolved' => 'Resolved',
                            'closed' =>'Closed'
                        ])
                        ->default('pending')
                        ->required(),

                        Forms\Components\Select::make('dept_id')
                        ->label('Department')
                        ->relationship('dept','dept_name'),

                        Forms\Components\FileUpload::make('img_before')
                        ->label('Picture Before')
                        ->directory('form-attachments')
                        ->visibility('public'),

                    ])->columns(2),


                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('problem.prob_id')
                    ->label('Problem_ID'),
                Tables\Columns\TextColumn::make('problem.emp_id')
                    ->label('Reporter'),
                Tables\Columns\TextColumn::make('problem.prob_desc')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('hazardLevel.Level')
                    ->label('Hazard Level'),
                Tables\Columns\TextColumn::make('hazardType.Desc')
                    ->label('Hazard Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img_before')
                    ->label('Picture Before'),
                Tables\Columns\TextColumn::make('dept.dept_name')
                    ->label('Department'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
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
            'index' => Pages\ListIssueReports::route('/'),
            'create' => Pages\CreateIssueReport::route('/create'),
            'edit' => Pages\EditIssueReport::route('/{record}/edit'),
        ];
    }
}
