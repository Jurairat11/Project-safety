<?php

namespace App\Filament\Resources\PCarResource\Pages;

use App\Filament\Resources\PCarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePCar extends CreateRecord
{
    protected static string $resource = PCarResource::class;
    protected static ?string $title = 'Create P-CAR';
}
