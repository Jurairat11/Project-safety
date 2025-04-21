<?php

namespace App\Filament\Resources\IssueResponsesResource\Pages;

use App\Filament\Resources\IssueResponsesResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;

class ViewIssueResponses extends ViewRecord
{
    protected static string $resource = IssueResponsesResource::class;

    protected static ?string $title = 'Issue Responses Details';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Issue Report')
                    ->schema([
                        Placeholder::make('report_id')
                            ->label('Report ID')
                            ->content(fn ($record) => $record->report_id),

                        Placeholder::make('form_no')
                            ->label('Form No.')
                            ->content(fn ($record) => $record->form_no),

                        Placeholder::make('safety_emp_id')
                            ->label('Safety ID')
                            ->content(fn ($record) => $record->safety_emp_id),

                        Placeholder::make('status')
                            ->label('Status')
                            ->content(fn ($record) =>
                            ucfirst(str_replace('_', ' ', $record->Issue_report->status))
    ),
                    ])
                    ->collapsed()
                    ->columns(4),

                Section::make('P-CAR Details')
                    ->schema([

                        Placeholder::make('cause')
                            ->label('Cause')
                            ->content(fn ($record) => $record->cause)
                            ->columnSpanFull(),

                        View::make('components.issue-responses-image')
                            ->label('Picture After')
                            ->viewData([
                                'path' => $this->getRecord()->img_after,
                            ])
                            ->columnSpanFull(),

                        Placeholder::make('temporary_act')
                            ->label('Temporary Action')
                            ->content(fn ($record) => $record->temporary_act)
                            ->columnSpan(2),

                        Placeholder::make('temp_due_date')
                            ->label('Temporary Due Date')
                            ->content(fn ($record) => $record->temp_due_date
                                ? Carbon::parse($record->temp_due_date)->format('d/m/Y')
                                : '-'),

                        Placeholder::make('temp_responsible')
                            ->label('Responsible')
                            ->content(fn ($record) => $record->temp_responsible ?
                                $record->temp_responsible : '-'),

                        Placeholder::make('permanent_act')
                            ->label('Permanent Action')
                            ->content(fn ($record) => $record->permanent_act)
                            ->columnSpan(2),

                        Placeholder::make('perm_due_date')
                            ->label('Permanent Due Date')
                            ->content(fn ($record) => $record->perm_due_date
                                ? Carbon::parse($record->perm_due_date)->format('d/m/Y')
                                : '-'),

                        Placeholder::make('perm_responsible')
                            ->label('Responsible')
                            ->content(fn ($record) => $record->perm_responsible ?
                                $record->perm_responsible : '-'),

                        Placeholder::make('preventive_act')
                            ->label('Preventive Action')
                            ->content(fn ($record) => $record->preventive_act)
                            ->columnSpan(3),

                        Placeholder::make('created_by')
                            ->label('Created By')
                            ->content(fn ($record) => $record->created_by),

                    ])
                    ->collapsed()
                    ->columns(4),
            ]);
    }

}
