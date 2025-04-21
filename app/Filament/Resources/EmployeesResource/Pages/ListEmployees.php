<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use App\Models\Employees;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create'),
        ];
    }
// กำหนดให้ safety และ admin เห็น List employee ท้ังหมด
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();

        return Employees::query()
            ->when(!in_array($user?->role, ['admin', 'safety']), function ($query) use ($user) {
                $query->whereHas('userRole', function ($q) use ($user) {
                    $q->where('role', $user->role);
                });
            })
            ->latest();
    }
}
