<?php

namespace App\Filament\Resources\IssueResponsesResource\Pages;

use App\Filament\Resources\IssueResponsesResource;
use App\Models\Issue_responses;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIssueResponses extends ListRecords
{
    protected static string $resource = IssueResponsesResource::class;
    protected static ?string $title = 'Issue Response List';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create'),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
        {
            return Issue_responses::query()->latest(); // ← เรียงจากรายการล่าสุด
        }
}
