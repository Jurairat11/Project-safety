<?php

namespace App\Filament\Resources\ProblemResource\Pages;

use App\Filament\Resources\ProblemResource;
use Filament\Resources\Pages\CreateRecord;
use App\Notifications\NewProblemNotification;
use App\Models\User;

class CreateProblem extends CreateRecord
{
    protected static string $resource = ProblemResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record; // ← ดึง Problem ที่เพิ่งสร้าง

        $safeties = User::where('role', 'safety')->get();

        foreach ($safeties as $safety) {
            $safety->notify(new NewProblemNotification($record));
        }
    }
}
