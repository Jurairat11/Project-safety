<?php

namespace App\Filament\Resources\HazardTypeResource\Pages;

use App\Filament\Resources\HazardTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHazardType extends EditRecord
{
    protected static string $resource = HazardTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
