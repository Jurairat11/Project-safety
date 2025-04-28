<?php

namespace App\Filament\Resources\ProblemResource\Pages;

use App\Filament\Resources\ProblemResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Components\View;


class ViewProblem extends ViewRecord
{
    protected static string $resource = ProblemResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Reported By')
                    ->schema([
                        Placeholder::make('emp_id')
                            ->label('Employee ID')
                            ->content(fn ($record) => $record->emp_id),

                        Placeholder::make('employee_name')
                            ->label('Full Name')
                            ->content(fn ($record) => optional($record->employee)->full_name),

                        Placeholder::make('department')
                            ->label('Department')
                            ->content(fn ($record) => optional($record->dept)->dept_name),
                    ])
                    ->columns(3),

                Section::make('Problem Details')
                    ->schema([
                        Placeholder::make('prob_desc')
                            ->label('Description')
                            ->content(fn ($record) => $record->prob_desc),

                        Placeholder::make('location')
                            ->label('Location')
                            ->content(fn ($record) => $record->location),

                            Placeholder::make('status')
                            ->label('Status')
                            ->content(fn ($record) => match ($record->status) {
                                'new' => 'New',
                                'reported' => 'Reported',
                                'in_progress' => 'In progress',
                                'pending_review' => 'Pending review',
                                'dismissed' => 'Dismissed',
                                'reopened' => 'Reopened',
                                'closed' => 'Closed',
                                default => 'Unknown',
                            })
                            ->extraAttributes(['class' => 'text-sm font-medium text-gray-800']),

                            Placeholder::make('dismiss_reason')
                                ->label('Reason for Dismissal')
                                ->content(fn ($record) => $record->dismiss_reason ?? '-')
                                ->visible(fn ($record) => $record->status === 'dismissed'),

                            View::make('components.problem-view-image')
                            ->label('Before Image')
                            ->viewData([
                                'path' => $this->getRecord()->pic_before,
                            ])
                            ->columnSpanFull()
                    ])
                    ->columns(4),
            ]);
    }
}
