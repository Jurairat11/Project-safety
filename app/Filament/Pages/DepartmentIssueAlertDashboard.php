<?php
namespace App\Filament\Pages;

use App\Models\Issue_report;
use App\Models\Problem;
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
            TextColumn::make('created_by')->label('Created By'),

            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'primary' => 'new',
                    'info' => 'reported',
                    'warning' => 'in_progress',
                    'success' => 'pending_review',
                    'secondary' => 'closed',
                    'warning' => 'reopened',
                    'danger' => 'dismissed',
                ])
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'new' => 'new',
                    'reported' => 'reported',
                    'in_progress' => 'in Progress',
                    'pending_review' => 'pending review',
                    'dismissed' => 'dismissed',
                    'reopened' => 'reopened',
                    'closed' => 'closed',
                    default => ucfirst($state),
                }),
            TextColumn::make('created_at')
            ->label('Created At')
            ->since(),
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
                ->visible(fn (Issue_report $record) => $record->status === 'reported')
                ->action(function (Issue_report $record) { $record->update(['status' => 'in_progress']);
                    \App\Models\Problem::where('prob_id', $record->prob_id)->update(['status' => 'in_progress']);
                    return redirect('/admin/issue-responses/create?report_id=' . $record->report_id);
                }),

            Tables\Actions\DeleteAction::make()
                ->label('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->successNotificationTitle('issue deleted successfully')
                ->successRedirectUrl(route('filament.admin.pages.department-issue-alert-dashboard')),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // ตรวจสอบว่าผู้ใช้เป็นแผนกหรือไม่
        if (auth()->user()->role !== 'department') {
            return null;
        }

        // นับเฉพาะที่เป็น reported และแผนกตรงกับผู้ใช้
        return \App\Models\Issue_report::where('status', 'reported')
            ->where('responsible_dept_id', auth()->user()->dept_id)
            ->count();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'department';
    }
}
?>
