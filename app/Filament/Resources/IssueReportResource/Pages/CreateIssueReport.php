<?php

namespace App\Filament\Resources\IssueReportResource\Pages;

use App\Filament\Resources\IssueReportResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Problem;

class CreateIssueReport extends CreateRecord
{
    protected static string $resource = IssueReportResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->prob_id) {
            Problem::where('prob_id', $record->prob_id)
                ->where('status', 'new')
                ->update(['status' => 'reported']);
        }
    }
}
