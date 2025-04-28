<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PCarResource\Pages;
use App\Filament\Resources\PCarResource\RelationManagers;
use App\Models\Issue_report;
use App\Models\Issue_responses;
use App\Models\Employees;
use App\Models\HazardLevel;
use App\Models\HazardType;
use App\Models\Dept;
use App\Models\Section;
use App\Models\P_car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PCarResource extends Resource
{
    protected static ?string $model = P_car::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralLabel = 'P-CAR Details';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('P-CAR')
                    ->label('P-CAR Details')
                    ->schema([
                        Forms\Components\Select::make('report_id')
                        ->label('Form no')
                        ->options(Issue_report::pluck('form_no','report_id'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $report = Issue_report::find($state);
                                $response = Issue_responses::where('report_id',$state)->first();
                                if ($report){
                                    $set('form_no', $report->form_no);
                                    $set('safety_dept', $report->safety_dept);
                                    $set('section', $report->section);
                                    $set('issue_date', $report->issue_date);
                                    $set('dead_line', $report->dead_line);
                                    $set('issue_desc', $report->issue_desc);
                                    $set('hazard_level_id', $report->hazard_level_id);
                                    $set('hazard_type_id', $report->hazard_type_id);
                                    $set('img_before', $report->img_before);
                                    $set('status', $report->status);
                                    $set('parent_id', $report->parent_id);
                                }

                                if ($response) {
                                    $set('img_after', $response->img_after);
                                    $set('cause', $response->cause);
                                    $set('temporary_act', $response->temporary_act);
                                    $set('temp_due_date', $response->temp_due_date);
                                    $set('temp_responsible',$response->temp_responsible);
                                    $set('permanent_act', $response->permanent_act);
                                    $set('perm_due_date', $response->perm_due_date);
                                    $set('perm_responsible', $response->perm_responsible);
                                    $set('preventive_act',$response->preventive_act);

                                }
                            }
                        }),

                        Forms\Components\Select::make('safety_dept')
                        ->label('Department')
                        ->options(Dept::pluck('dept_name','dept_id'))
                        ->required(),

                        Forms\Components\Select::make('section')
                        ->label('Section')
                        ->options(Section::pluck('sec_name','sec_id'))
                        ->required(),

                        Forms\Components\DatePicker::make('issue_date')
                        ->label('Create date')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),

                        Forms\Components\DatePicker::make('dead_line')
                        ->label('Due date')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),

                    ])->columns(5),

                    Forms\Components\Section::make('P-CAR Details (Safety)')
                        ->schema([

                        Forms\Components\Textarea::make('issue_desc')
                        ->label('Description')
                        ->required()
                        ->columnSpanFull(),

                        Forms\Components\Select::make('hazard_level_id')
                        ->label('Hazard level')
                        ->options(HazardLevel::pluck('Level','hazard_level_id'))
                        ->required(),

                        Forms\Components\Select::make('hazard_type_id')
                        ->label('Hazard type')
                        ->options(HazardType::pluck('Desc','hazard_type_id'))
                        ->required(),

                        /*Forms\Components\FileUpload::make('img_before')
                        ->label('Picture before')
                        ->directory('form-attachments')
                        ->required(),


                        Forms\Components\FileUpload::make('img_after')
                        ->label('Picture after')
                        ->directory('form-attachments')
                        ->required(),*/
                    ])->columns(2),

                    Forms\Components\Section::make('P-CAR Details (Department)')
                    ->schema([

                        Forms\Components\Textarea::make('cause')
                        ->label('Cause')
                        ->required()
                        ->columnSpanFull(),

                        Forms\Components\Textarea::make('temporary_act')
                        ->label('Temporary action'),

                        Forms\Components\Textarea::make('permanent_act')
                        ->label('Permanent action'),

                        Forms\Components\DatePicker::make('temp_due_date')
                        ->label('Temporary due date')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                        Forms\Components\DatePicker::make('perm_due_date')
                        ->label('Permanent due date')
                        ->native(false)
                        ->displayFormat('d/m/Y'),

                        Forms\Components\Select::make('temp_responsible')
                        ->label('Temporary responsible')
                        ->options(
                            Employees::all()->pluck('full_name', 'emp_id')
                        ),

                        Forms\Components\Select::make('perm_responsible')
                        ->label('Permanent responsible')
                        ->options(
                            Employees::all()->pluck('full_name', 'emp_id')
                        ),

                        Forms\Components\Textarea::make('preventive_act')
                        ->label('Preventive action')
                        ->default('-')
                        ->required()
                        ->columnSpanFull(),

                        Forms\Components\TextInput::make('status')
                        ->label('Status'),

                        Forms\Components\Select::make('parent_id')
                        ->label('Form no')
                        ->relationship('parent','form_no')
                        ->columnSpan(1),


                    ])->columns(2),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('form_no')
                ->label('Form No')
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
            'index' => Pages\ListPCars::route('/'),
            'create' => Pages\CreatePCar::route('/create'),
            'edit' => Pages\EditPCar::route('/{record}/edit'),
        ];
    }
}
