<?php

namespace App\Filament\Resources\ProblemResource\Pages;

use App\Filament\Resources\ProblemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Problem;
use Illuminate\Database\Eloquent\Builder;

class ListProblems extends ListRecords
{
    protected static string $resource = ProblemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
        {
            return Problem::query()->latest(); // ← เรียงจากรายการล่าสุด
        }
}
