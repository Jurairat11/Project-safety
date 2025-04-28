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
use App\Models\User;
use App\Models\Problem;
use Filament\Notifications\Notification;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Psr7\Request;


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
                            ->content(fn () => optional($response)->temporary_act ?? '-')
                            ->columnSpan(2),

                        Placeholder::make('temp_due_date')
                            ->label('Temporary Due Date')
                            ->content(fn () => optional($response)->temp_due_date?->format('d/m/Y') ?? '-'),

                        Placeholder::make('temp_responsible')
                            ->label('Responsible')
                            ->content(fn () => optional($response?->tempResponsible)?->full_name ?? '-'),

                        Placeholder::make('permanent_act')
                            ->label('Permanent Action')
                            ->content(fn () => optional($response)->permanent_act ?? '-')
                            ->columnSpan(2),

                        Placeholder::make('perm_due_date')
                            ->label('Permanent Due Date')
                            ->content(fn () => optional($response)->perm_due_date?->format('d/m/Y') ?? '-'),

                        Placeholder::make('perm_responsible')
                            ->label('Responsible')
                            ->content(fn () => optional($response?->permResponsible)?->full_name ?? '-'),

                        Placeholder::make('preventive_act')
                            ->label('Preventive Action')
                            ->content(fn () => optional($response)->preventive_act ?? '-')
                            ->columnSpan(2),

                        Placeholder::make('remark')
                            ->label('Remark')
                            ->content(fn () => optional($response)->remark ?? '-'),

                        /*Placeholder::make('responded_at')
                            ->label('Responded At')
                            ->content(fn () => optional($response)->created_at?->format('d/m/Y H:i') ?? '-'),*/
                    ])
                    ->columns(4)
                    ->collapsed(),
            ]);
    }

        protected function getActions(): array
    {
        return [

            /*Action::make('printCar')
            ->label('Print CAR')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->action(function ($record) {
                $formNo = $record->form_no;
                $issue = $record;
                $params = [
                    'form_no' => $issue->form_no,
                    'safety_dept' => $issue->safety_dept,
                    'section' => $issue->section,
                    'issue_date' => $issue->issue_date,
                    'dead_line' => $issue->dead_line,
                    'issue_desc' => $issue->issue_desc,
                    'hazard_level_id' => $issue->hazard_level_id,
                    'hazard_type_id' => $issue->hazard_type_id,
                    'img_before' => $issue->img_before,
                    'img_after' => $issue->img_after,
                    'cause' => $issue->cause,
                    'temporary_act' => $issue->temporary_act,
                    'temp_due_date' => $issue->temp_due_date,
                    'temp_responsible' => $issue->temp_responsible,
                    'permanent_act' => $issue->permanent_act,
                    'perm_due_date' => $issue->perm_due_date,
                    'perm_responsible' => $issue->perm_responsible,
                    'preventive_act' => $issue->preventive_act,
                    'status' => $issue->status,
                    'parent_id' => $issue->parent_id,
                ];

                $jasperServer = env('JASPER_SERVER');
                $jasperUser = env('JASPER_USER');
                $jasperPass = env('JASPER_PASSWORD');

                try {
                    session_start();
                    $jar = new SessionCookieJar('CookieJar', true);

                    $client = new Client(['cookies' => $jar]);
                    $loginUrl = "$jasperServer/jasperserver/rest_v2/login?j_username=$jasperUser&j_password=$jasperPass";
                    $client->get($loginUrl); // login session

                    $reportUrl = "$jasperServer/jasperserver/rest_v2/reports/reports/car_form.pdf?form_no=$formNo";
                    $response = $client->get($reportUrl);

                    if ($response->getStatusCode() == 200) {
                        return response()->streamDownload(function () use ($response) {
                            echo $response->getBody();
                        }, "CAR_$formNo.pdf");
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('Unable to print report')
                            ->body('Server Report not responding')
                            ->send();
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Invalid request')
                        ->body($e->getMessage())
                        ->send();
                }
            }),*/

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

                    // ปิดใบ CAR ก่อนหน้า
                    if ($this->record->parent_id) {
                        $this->record->parent()->update(['status' => 'closed']);
                    }

                    // ปิดปัญหาต้นทาง
                    if ($this->record->prob_id) {
                        Problem::where('prob_id', $this->record->prob_id)
                            ->update(['status' => 'closed']);
                    }

                    // ปิด responses ที่เกี่ยวข้อง
                    Issue_responses::where('report_id', $this->record->report_id)
                        ->update(['status' => 'closed']);

                    //แจ้งพนักงานผู้แจ้งปัญหา
                    $problem = Problem::where('prob_id', $this->record->prob_id)->first();

                    if ($problem) {
                        $employee = User::where('emp_id', $problem->emp_id)->first();
                        if ($employee) {
                            Notification::make()
                                ->color('success')
                                ->icon('heroicon-o-check-circle')
                                ->title('Your issue has been solved')
                                ->body("Your Problem ID: {$problem->prob_id} has been resolved and closed.")
                                ->sendToDatabase($employee);
                        }
                    }

                //แจ้งหน่วยงานที่รับผิดชอบ
                $departmentUsers = User::where('role', 'department')
                    ->where('dept_id', $this->record->responsible_dept_id)
                    ->get();

                foreach ($departmentUsers as $user) {
                    Notification::make()
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->title('P-CAR completed')
                        ->body("The P-CAR for Form no: {$this->record->form_no} has been completed successfully.")
                        ->sendToDatabase($user);
                }
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

                    // ปิดปัญหาต้นทาง
                    if ($this->record->prob_id) {
                        Problem::where('prob_id', $this->record->prob_id)
                            ->update(['status' => 'reopened']);
                    }

                    // ปิด responses ที่เกี่ยวข้อง
                    Issue_responses::where('report_id', $this->record->report_id)
                        ->update(['status' => 'reopened']);

                    // ดึงข้อมูลปัญหา
                    $problem = Problem::where('prob_id', $this->record->prob_id)->first();

                    //แจ้ง employee
                    if ($problem) {
                        $employee = User::where('emp_id', $problem->emp_id)->first();

                        if ($employee) {
                            Notification::make()
                                ->color('warning')
                                ->icon('heroicon-o-exclamation-triangle')
                                ->title('Issue unresolved')
                                ->body("Your Problem ID: {$problem->prob_id} has not been resolved. A new P-CAR will be opened.")
                                ->sendToDatabase($employee);
                        }
                    }

                    //แจ้งหน่วยงานที่รับผิดชอบ
                    $deptUsers = User::where('role', 'department')
                        ->where('dept_id', $this->record->responsible_dept_id)
                        ->get();

                    foreach ($deptUsers as $user) {
                        Notification::make()
                            ->color('warning')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->title('P-CAR reopened')
                            ->body("The resolution for Form no: {$this->record->form_no} was not accepted. A new P-CAR will be created.")
                            ->sendToDatabase($user);
                    }

                    // ส่งค่าผ่าน query string
                    return redirect()->route('filament.admin.resources.issue-reports.create', [
                        'prob_id'              => $this->record->prob_id,
                        'safety_dept'          => $this->record->safety_dept,
                        'section'              => $this->record->section,
                        'issue_date'           => $this->record->issue_date,
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
                        'parent_id'           => $this->record->report_id, //เก็บ problem_id ของอันก่อนหน้า เพื่อสร้าง CAR ใหม่
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
