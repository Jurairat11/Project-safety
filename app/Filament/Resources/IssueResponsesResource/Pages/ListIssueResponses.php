<?php

namespace App\Filament\Resources\IssueResponsesResource\Pages;

use App\Filament\Resources\IssueResponsesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIssueResponses extends ListRecords
{
    protected static string $resource = IssueResponsesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
