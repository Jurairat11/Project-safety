<?php

namespace App\Filament\Resources\IssueReportResource\Pages;

use App\Filament\Resources\IssueReportResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Actions\Action;
use App\Models\Issue_responses;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;


class ViewIssueReport extends ViewRecord
{

    protected static string $resource = IssueReportResource::class;
    protected static ?string $title = 'Issue Report Details';

    public function form(Form $form): Form
    {
        $response = $this->getRecord()->responses->first();

        return $form
            ->schema([
                Section::make('Problem Report')
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

                Section::make('P-CAR Details (Safety)')
                    ->schema([

                        Placeholder::make('form_no')
                            ->label('Form No.')
                            ->content(fn ($record) => $record->form_no),

                        Placeholder::make('safety_dept')
                            ->label('Safety Department')
                            ->content(fn ($record) => optional($record->safetyDept)->dept_name),

                        Placeholder::make('section')
                            ->label('Safety Section')
                            ->content(fn ($record) => $record->sectionRelation?->sec_name ?? '-'),

                        Placeholder::make('issue_date')
                            ->label('Created Date')
                            ->content(fn ($record) => Carbon::parse($record->issue_date)->format('d/m/Y')),

                        Placeholder::make('dead_line')
                            ->label('Dead Line')
                            ->content(fn ($record) => Carbon::parse($record->dead_line)->format('d/m/Y')),

                        Placeholder::make('parent_id')
                            ->label('From CAR')
                            ->content(fn ($record) => $record->parent?->form_no ?? '-'),

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

                    Section::make('P-CAR Details (Department)')
                    ->schema([
                        Placeholder::make('cause')
                            ->label('Cause')
                            ->content(fn () => optional($response)->cause ?? '-'),

                            View::make('components.issue-responses-image')
                            ->label('Picture After')
                            ->viewData([
                                // ถ้าไม่มี response หรือ response->img_after ก็จะเป็น null
                                'path' => optional($response)->img_after,
                            ])
                            ->columnSpanFull(),

                        Placeholder::make('temporary_act')
                            ->label('Temporary Action')
                            ->content(fn () => optional($response)->temporary_act ?? '-'),

                        Placeholder::make('permanent_act')
                            ->label('Permanent Action')
                            ->content(fn () => optional($response)->permanent_act ?? '-'),

                        Placeholder::make('temp_due_date')
                            ->label('Temp Due Date')
                            ->content(fn () => optional($response)->temp_due_date?->format('d/m/Y') ?? '-'),

                        Placeholder::make('perm_due_date')
                            ->label('Perm Due Date')
                            ->content(fn () => optional($response)->perm_due_date?->format('d/m/Y') ?? '-'),

                        Placeholder::make('temp_responsible')
                            ->label('Temp Responsible')
                            ->content(fn () => optional($response?->tempResponsible)?->full_name ?? '-'),

                        Placeholder::make('perm_responsible')
                            ->label('Perm Responsible')
                            ->content(fn () => optional($response?->permResponsible)?->full_name ?? '-'),

                        Placeholder::make('preventive_act')
                            ->label('Preventive Action')
                            ->content(fn () => optional($response)->preventive_act ?? '-'),

                        Placeholder::make('remark')
                            ->label('Remark')
                            ->content(fn () => optional($response)->remark ?? '-'),

                        /*Placeholder::make('responded_at')
                            ->label('Responded At')
                            ->content(fn () => optional($response)->created_at?->format('d/m/Y H:i') ?? '-'),*/
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

        protected function getActions(): array
    {
        return [
            // ปุ่มอนุมัติให้ปิด CAR
            Action::make('approve')
                ->label('Approve & Close')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => $this->record->status === 'pending_review')
                ->requiresConfirmation()
                ->action(function () {
                    // ปิดใบปัจจุบัน
                    $this->record->update(['status' => 'closed']);

                    // ถ้ามีใบก่อนหน้า (parent_id) → ปิดด้วย
                    if ($this->record->parent_id) {
                        $this->record->parent()->update(['status' => 'closed']);
                    }

                    // ปิดปัญหาต้นทาง (prob_id) ถ้ามี
                    if ($this->record->prob_id) {
                        \App\Models\Problem::where('prob_id', $this->record->prob_id)
                            ->update(['status' => 'closed']);
                    }

                     // ปิด responses ที่เกี่ยวข้อง
                    Issue_responses::where('report_id', $this->record->id)
                    ->update(['status' => 'closed']);

                }),

            // ปุ่มขอให้แก้ไขใหม่ (Request Rework)
            Action::make('rework')
                ->label('Request Rework')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->visible(fn () => $this->record->status === 'pending_review')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'reopened']);

                    // ส่งค่าผ่าน query string
                    return redirect()->route('filament.admin.resources.issue-reports.create', [
                        'prob_id'              => $this->record->prob_id,
                        'safety_dept'          => $this->record->safety_dept,
                        'section'              => $this->record->section,
                        'issue_date'          => $this->record->issue_date,
                        'dead_line'            => $this->record->dead_line,
                        'issue_desc'           => $this->record->issue_desc,
                        'hazard_level_id'      => $this->record->hazard_level_id,
                        'hazard_type_id'       => $this->record->hazard_type_id,
                        'img_before'           => $this->record->img_before,
                        'responsible_dept_id'  => $this->record->responsible_dept_id,
                        'parent_id'            => $this->record->report_id,
                    ]);
                }),

                Action::make('createNewCAR')
                ->label('Create New CAR')
                ->color('primary')
                ->icon('heroicon-o-document-plus')
                ->visible(fn () => $this->record->status === 'reopened')
                ->requiresConfirmation()
                ->action(function () {
                    return redirect()->route('filament.admin.resources.issue-reports.create', [
                        'parent_id'           => $this->record->report_id,
                        'prob_id'             => $this->record->prob_id,
                        'safety_dept'         => $this->record->safety_dept,
                        'section'             => $this->record->section,
                        'issue_date'          => $this->record->issue_date,
                        'dead_line'           => $this->record->dead_line,
                        'issue_desc'          => $this->record->issue_desc,
                        'hazard_level_id'     => $this->record->hazard_level_id,
                        'hazard_type_id'      => $this->record->hazard_type_id,
                        'img_before'          => $this->record->img_before,
                        'created_by'         => $this->record->created_by,
                        'responsible_dept_id' => $this->record->responsible_dept_id,
                    ]);
                }),
            ];
    }
}
