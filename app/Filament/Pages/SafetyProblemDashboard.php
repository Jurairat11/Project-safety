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
            TextColumn::make('prob_id')->label('Problem ID'),
            TextColumn::make('emp_id')->label('Reported By')->searchable(),
            TextColumn::make('dept.dept_name')->label('Department'),
            TextColumn::make('prob_desc')->label('Description')->limit(50)->searchable(),
            ImageColumn::make('pic_before')->label('Before Image')->height(60),
            BadgeColumn::make('status')->colors([
                'primary' => 'new',
                'info' => 'reported',
                'warning' => 'in_progress',
                'success' => 'resolved',
                'danger' => 'dismissed',
                'secondary' => 'closed',
            ])->formatStateUsing(fn ($state) => match ($state) {
                'new' => 'new',
                'reported' => 'reported',
                'in_progress'=> 'in progress',
                'resolved' => 'resolved',
                'dismissed' => 'dismissed',
                'closed' => 'closed',
                default => ucfirst($state),
            }),
            TextColumn::make('created_at')->label('Created')->since()
            ];

        }

        protected function getTableBulkActions(): array
        {
            return [
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
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

            /*Tables\Actions\DeleteAction::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->successNotificationTitle('Problem deleted successfully')
                ->successRedirectUrl(route('filament.admin.pages.safety-problem-dashboard')),*/

            Action::make('dismiss')
                ->label('Dismiss')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status !== 'dismissed')
                ->action(fn ($record) => $record->update(['status' => 'dismissed']))

        ];
    }

    protected function getTableTabs(): array
    {
        return [
            'new' => [
                'label' => 'New',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'new')->latest(),
            ],
            'reported' => [
                'label' => 'Reported',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'reported')->latest(),
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'in_progress')->latest(),
            ],
            'pending_review' => [
                'label' => 'Pending Review',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'pending_review')->latest(),
            ],
            'dismissed' => [
                'label' => 'Dismissed',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'dismissed')->latest(),
            ],
            'closed' => [
                'label' => 'Closed',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'closed')->latest(),
            ],
            'reopened' => [
                'label' => 'Reopened',
                'modifyQueryUsing' => fn ($query) => $query->where('status', 'reopened')->latest(),
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
    public int $pendingReviewProblems;
    public int $dismissedProblems;

    public int $closedProblems;

    public int $reopenedProblems;

    public function mount(): void
    {
        $this->totalProblems = Problem::count();
        $this->newProblems = Problem::where('status', 'new')->count();
        $this->reportedProblems = Problem::where('status', 'reported')->count();
        $this->inProgressProblems = Problem::where('status', 'in_progress')->count();
        $this->pendingReviewProblems = Problem::where('status', 'pending_review')->count();
        $this->dismissedProblems = Problem::where('status', 'dismissed')->count();
        $this->closedProblems = Problem::where('status', 'closed')->count();
        $this->reopenedProblems = Problem::where('status', 'reopened')->count();
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
