<?php

namespace App\Filament\Pages;

use App\Models\Problem;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;


class SafetyProblemDashboard extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationLabel = 'New Problem Alerts';
    protected static ?string $title = 'Safety Dashboard';
    protected static string $view = 'filament.pages.safety-problem-dashboard';

    protected function getTableQuery(): Builder
    {
        return Problem::query()
            ->where('status', 'new')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [

            TextColumn::make('emp_id')
                ->label('Reported By')
                ->searchable(),

            TextColumn::make('dept.dept_name')
                ->label('Department'),

            TextColumn::make('prob_desc')
                ->label('Description')
                ->limit(50)
                ->searchable(),

            TextColumn::make('location')
                ->label('Location'),

            ImageColumn::make('pic_before')
                ->label('Before Image')
                ->height(60),

            BadgeColumn::make('status')
                ->colors([
                    'primary' => 'new',
                    'success' => 'reported',
                    'danger' => 'dismissed',
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'new' => 'New',
                    'reported' => 'Reported',
                    'dismissed' => 'Dismissed',
                    default => ucfirst($state),
                }),

            TextColumn::make('created_at')
                ->label('Created')
                ->since()
        ];
    }

    protected function getTableActions(): array
{
    return [

        Action::make('view')
            ->label('View')
            ->color('primary')
            ->icon('heroicon-o-eye')
            ->url(fn (Problem $record) => route('filament.admin.resources.problems.view', ['record' => $record->getKey()]))
            ->openUrlInNewTab(),

        Action::make('accept')
            ->label('Accept')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->action(function (Problem $record) {
                $record->update(['status' => 'reported']);
            })
            ->after(function (Problem $record) {
                // ไปหน้า Issue Report สร้างใหม่ โดยส่ง prob_id ไป
                return redirect('/admin/resources/issue-reports/create?prob_id=' . $record->prob_id);
            }),
    ];
}


    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'safety';
    }

}
