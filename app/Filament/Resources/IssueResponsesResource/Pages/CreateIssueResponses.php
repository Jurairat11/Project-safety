<?php

namespace App\Filament\Resources\IssueResponsesResource\Pages;

use App\Filament\Resources\IssueResponsesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIssueResponses extends CreateRecord
{
    protected static string $resource = IssueResponsesResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->report_id) {
            $report = \App\Models\Issue_report::find($record->report_id);

            if ($report) {
                $report->update(['status' => 'pending_review']);

                \App\Models\Problem::where('prob_id', $report->prob_id)
                    ->update(['status' => 'pending_review']);
            }
        }
    }


}
