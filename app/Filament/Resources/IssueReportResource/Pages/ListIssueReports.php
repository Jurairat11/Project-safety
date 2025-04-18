<?php

namespace App\Filament\Resources\IssueReportResource\Pages;

use App\Filament\Resources\IssueReportResource;
use App\Models\Issue_report;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIssueReports extends ListRecords
{
    protected static string $resource = IssueReportResource::class;
    protected static ?string $title = 'Issue Report List';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create'),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
        {
            return Issue_report::query()->latest(); // ← เรียงจากรายการล่าสุด
        }
}
