<?php

namespace App\Filament\Resources\IssueReportResource\Pages;

use App\Filament\Resources\IssueReportResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;

class ViewIssueReport extends ViewRecord
{
    protected static string $resource = IssueReportResource::class;
    protected static ?string $title = 'Issue Report Details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Issue Report')
                    ->schema([
                        Placeholder::make('prob_id')
                            ->label('Problem ID')
                            ->content(fn ($record) => $record->prob_id),

                        Placeholder::make('emp_id')
                            ->label('Employee ID')
                            ->content(fn ($record) => $record->emp_id),

                        Placeholder::make('dept_id')
                            ->label('Department')
                            ->content(fn ($record) => optional($record->dept)->dept_name),

                        Placeholder::make('prob_desc')
                            ->label('Problem Description')
                            ->content(fn ($record) => $record->prob_desc)
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columns(3),

                Section::make('P-CAR Details')
                    ->schema([

                        Placeholder::make('form_no')
                            ->label('Form No.')
                            ->content(fn ($record) => $record->form_no),

                        Placeholder::make('safety_dept')
                            ->label('Safety Department')
                            ->content(fn ($record) => optional($record->safetyDept)->dept_name),

                        Placeholder::make('section')
                            ->label('Section')
                            ->content(fn ($record) => $record->section),

                        Placeholder::make('issue_date')
                            ->label('Created Date')
                            ->content(fn ($record) => Carbon::parse($record->issue_date)->format('d/m/Y')),

                        Placeholder::make('dead_line')
                            ->label('Dead Line')
                            ->content(fn ($record) => Carbon::parse($record->dead_line)->format('d/m/Y')),

                        Placeholder::make('issue_desc')
                            ->label('Issue Description')
                            ->content(fn ($record) => $record->issue_desc)
                            ->columnSpanFull(),

                        Placeholder::make('hazard_level_id')
                            ->label('Hazard Level')
                            ->content(fn ($record) => optional($record->hazardLevel)->Level),

                        Placeholder::make('hazard_type_id')
                            ->label('Hazard Type')
                            ->content(fn ($record) => optional($record->hazardType)->Desc),

                        Placeholder::make('status')
                            ->label('Status')
                            ->content(fn ($record) => ucfirst(str_replace('_', ' ', $record->status))),

                        Placeholder::make('responsible_dept_id')
                            ->label('Responsible Department')
                            ->content(fn ($record) => optional($record->responsibleDept)->dept_name),

                        View::make('components.issue-report-image')
                            ->label('Picture Before')
                            ->viewData([
                                'path' => $this->getRecord()->img_before,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columns(4),
            ]);
    }
}
