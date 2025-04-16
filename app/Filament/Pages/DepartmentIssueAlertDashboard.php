<?php
namespace App\Filament\Pages;

use App\Models\Issue_report;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BadgeColumn;

class DepartmentIssueAlertDashboard extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Issue Alerts';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $title = 'Department Alerts';
    protected static string $view = 'filament.pages.department-issue-alert-dashboard';

    protected function getTableQuery(): Builder
    {
        return Issue_report::query()
            ->where('responsible_dept_id', auth()->user()->dept_id)
            ->whereNotIn('status', ['resolved', 'closed']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('report_id')->label('Issue ID'),
            TextColumn::make('issue_desc')->label('Issue Desc'),

            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'primary' => 'new',
                    'info' => 'reported',
                    'warning' => 'in_progress',
                    'success' => 'resolved',
                    'danger' => 'dismissed',
                ])
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'new' => 'New',
                    'reported' => 'Reported',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'dismissed' => 'Dismissed',
                    default => ucfirst($state),
                }),
            TextColumn::make('created_at')->since(),
        ];
    }

    protected function getTableActions(): array
    {
        return [

            Action::make('view')
            ->label('View')
            ->icon('heroicon-o-eye')
            ->color('primary')
            ->url(fn ($record) => route('filament.admin.resources.issue-reports.view', ['record' => $record]))
            ->openUrlInNewTab(),

            Action::make('accept')
            ->label('Accept')
            ->icon('heroicon-o-check')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn ($record) => $record->status === 'reported')
            ->action(function ($record) {
                // อัปเดต status ของ issue report
                $record->update(['status' => 'In progress']);

                // อัปเดต status ของ problem ที่เชื่อมกับ issue report นี้
                if ($record->prob_id) {
                    \App\Models\Problem::where('prob_id', $record->prob_id)
                        ->update(['status' => 'In progress']);
                }
            }),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'department';
    }
}
?>
