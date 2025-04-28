<?php

namespace App\Filament\Resources\PCarResource\Pages;

use App\Filament\Resources\PCarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPCars extends ListRecords
{
    protected static string $resource = PCarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
