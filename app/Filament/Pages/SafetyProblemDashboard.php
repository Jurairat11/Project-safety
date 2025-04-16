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
use Filament\Navigation\NavigationItem;


class SafetyProblemDashboard extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationLabel = 'Problem Alerts';
    protected static ?string $title = 'Safety Dashboard';
    protected static string $view = 'filament.pages.safety-problem-dashboard';

    protected function getTableQuery(): Builder
    {
        return Problem::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('emp_id')->label('Reported By')->searchable(),
            TextColumn::make('dept.dept_name')->label('Department'),
            TextColumn::make('prob_desc')->label('Description')->limit(50)->searchable(),
            TextColumn::make('location')->label('Location'),
            ImageColumn::make('pic_before')->label('Before Image')->height(60),
            BadgeColumn::make('status')->colors([
                'primary' => 'new',
                'success' => 'reported',
                'warning' => 'resolved',
                'danger' => 'dismissed',
            ])->formatStateUsing(fn ($state) => match ($state) {
                'new' => 'New',
                'reported' => 'Reported',
                'resolved' => 'Resolved',
                'dismissed' => 'Dismissed',
                default => ucfirst($state),
            }),
            TextColumn::make('created_at')->label('Created')->since()
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
                ->action(fn (Problem $record) => redirect('/admin/issue-reports/create?prob_id=' . $record->prob_id))
                ->visible(fn (Problem $record) => $record->status === 'new'),
        ];
    }

    protected function getTableTabs(): array
    {
        return [
            'new' => [
                'label' => 'New',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'new'),
            ],
            'reported' => [
                'label' => 'Reported',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'reported'),
            ],
            'resolved' => [
                'label' => 'Resolved',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'resolved'),
            ],
            'dismissed' => [
                'label' => 'Dismissed',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'dismissed'),
            ],
            'all' => [
                'label' => 'All',
            ],
        ];
    }

    public int $totalProblems;
    public int $newProblems;
    public int $reportedProblems;

    public int $inProgressProblems;

    public int $resolvedProblems;
    public int $dismissedProblems;

    public function mount(): void
    {
        $this->totalProblems = Problem::count();
        $this->newProblems = Problem::where('status', 'new')->count();
        $this->reportedProblems = Problem::where('status', 'reported')->count();
        $this->inProgressProblems = Problem::where('status', 'in_progress')->count();
        $this->resolvedProblems = Problem::where('status', 'resolved')->count();
        $this->dismissedProblems = Problem::where('status', 'dismissed')->count();
    }

    public static function getNavigationBadge(): ?string
    {
        // นับจำนวนปัญหาใหม่
        $count = \App\Models\Problem::where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    protected function getDefaultTableTab(): ?string
    {
        return 'new';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'safety';
    }
}
