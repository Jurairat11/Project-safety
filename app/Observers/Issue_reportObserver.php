<?php

namespace App\Observers;

use App\Models\Issue_report;
use App\Models\User;
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
                ->title('Problem has been accepted')
                ->body("Form No: {$issue_report->form_no}")
                ->sendToDatabase($user);

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
