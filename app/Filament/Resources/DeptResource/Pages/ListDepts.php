<?php

namespace App\Filament\Resources\DeptResource\Pages;

use App\Filament\Resources\DeptResource;
use App\Models\Dept;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepts extends ListRecords
{
    protected static string $resource = DeptResource::class;
    protected static ?string $title = 'Department';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create'),
        ];
    }

}
