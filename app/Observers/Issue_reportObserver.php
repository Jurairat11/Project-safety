<?php

namespace App\Observers;

use App\Models\Issue_report;
use App\Models\User;
use App\Models\Problem;
use Filament\Notifications\Notification;

class Issue_reportObserver
{
    /**
     * Handle the Issue_report "created" event.
     */
    public function created(Issue_report $issue_report): void
    {
        $user = User::where('emp_id', $issue_report->emp_id)->first();

        if ($user) {
            Notification::make()
                ->icon('heroicon-o-document-check')
                ->iconColor('success')
                ->title('Problem has been accepted')
                ->body("Form no: {$issue_report->form_no}")
                ->sendToDatabase($user);

            User::where('role', 'department')
                ->where('dept_id', $issue_report->responsible_dept_id)
                ->get()
                ->each(function ($user) use ($issue_report) {
                    Notification::make()
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->title('New P-CAR has been sent')
                        ->body("Issue report ID: {$issue_report->report_id}" . "Form no: {$issue_report->form_no}")
                        ->sendToDatabase($user);
                });
        }

        // เช็กว่ามีใบ CAR ก่อนหน้านี้ด้วย prob_id เดียวกัน
            $existing = Issue_report::where('prob_id', $issue_report->prob_id)
            ->where('report_id', '!=', $issue_report->report_id) // ไม่ใช่ตัวที่เพิ่งสร้าง
            ->exists();

        if ($existing) {
            // แจ้งพนักงานที่แจ้งปัญหา
            $problem = Problem::where('prob_id', $issue_report->prob_id)->first();

            if ($problem) {
                $employee = User::where('emp_id', $problem->emp_id)->first();

                if ($employee) {
                    Notification::make()
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->title('New P-CAR created')
                        ->body("New P-CAR Form no: {$issue_report->form_no} has been created from Problem ID: {$issue_report->prob_id}")
                        ->sendToDatabase($employee);
                }
            }

            // แจ้งแผนกที่รับผิดชอบ
            $deptUsers = User::where('role', 'department')
                ->where('dept_id', $issue_report->responsible_dept_id)
                ->get();

            foreach ($deptUsers as $user) {
                Notification::make()
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->title('New P-CAR created')
                    ->body("New P-CAR Form no: {$issue_report->form_no} has been created from Problem ID: {$issue_report->prob_id}")
                    ->url(route('filament.admin.resources.issue-reports.view', $issue_report->report_id))
                    ->sendToDatabase($user);
                }
    }
}


    /**
     * Handle the Issue_report "updated" event.
     */
    public function updated(Issue_report $issue_report): void
    {
        //
    }

    /**
     * Handle the Issue_report "deleted" event.
     */
    public function deleted(Issue_report $issue_report): void
    {
        //
    }

    /**
     * Handle the Issue_report "restored" event.
     */
    public function restored(Issue_report $issue_report): void
    {
        //
    }

    /**
     * Handle the Issue_report "force deleted" event.
     */
    public function forceDeleted(Issue_report $issue_report): void
    {
        //
    }
}
