<?php

namespace App\Observers;

use App\Models\Issue_responses;
use App\Models\User;
use App\Models\Problem;
use Filament\Notifications\Notification;

class Issue_responsesObserver
{
    /**
     * Handle the Issue_responses "created" event.
     */
    public function created(Issue_responses $issue_responses): void
    {
        User::where('role', 'safety')->get()
        ->each(fn ($user) =>
            Notification::make()
                ->color('success')
                ->icon('heroicon-o-document-check')
                ->title('Department response submitted')
                ->body("Issue report Form no: {$issue_responses->form_no} has been responded to.")
                ->sendToDatabase($user)
        );
        // ดึง issue_report จาก response
        $issueReport = \App\Models\Issue_report::find($issue_responses->report_id);

        // ดึง problem ที่เกี่ยวข้อง
        $problem = \App\Models\Problem::where('prob_id', $issueReport->prob_id)->first();

        // ดึง user จาก emp_id ที่แจ้งปัญหา
        $user = \App\Models\User::where('emp_id', $problem->emp_id)->first();

        if ($user) {
            Notification::make()
                ->icon('heroicon-o-document-check')
                ->title('Your issue is being handled')
                ->color('success')
                ->body("Your issue Problem ID: {$problem->prob_id} has received a response from the department.")
                ->sendToDatabase($user);
}

    }

    /**
     * Handle the Issue_responses "updated" event.
     */
    public function updated(Issue_responses $issue_responses): void
    {
        //
    }

    /**
     * Handle the Issue_responses "deleted" event.
     */
    public function deleted(Issue_responses $issue_responses): void
    {
        //
    }

    /**
     * Handle the Issue_responses "restored" event.
     */
    public function restored(Issue_responses $issue_responses): void
    {
        //
    }

    /**
     * Handle the Issue_responses "force deleted" event.
     */
    public function forceDeleted(Issue_responses $issue_responses): void
    {
        //
    }
}
