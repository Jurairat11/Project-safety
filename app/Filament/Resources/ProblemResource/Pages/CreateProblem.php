<?php

namespace App\Filament\Resources\ProblemResource\Pages;

use App\Filament\Resources\ProblemResource;
use Filament\Resources\Pages\CreateRecord;
use App\Notifications\NewProblemNotification;
use App\Models\User;
use App\Models\Problem;


class CreateProblem extends CreateRecord
{
    protected static string $resource = ProblemResource::class;

    public static function afterCreate(Problem $record): void
{
    $safeties = User::where('role', 'safety')->get();

    foreach ($safeties as $safety) {
        $safety->notify(new NewProblemNotification($record));
    }
}
}
