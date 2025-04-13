<?php

namespace App\Filament\Resources\IssueReportResource\Pages;

use App\Filament\Resources\IssueReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIssueReports extends ListRecords
{
    protected static string $resource = IssueReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
