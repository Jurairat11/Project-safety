<?php
namespace App\Filament\Pages;

use App\Models\Issue_report;
use App\Models\Problem;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\User;


class DepartmentIssueAlertDashboard extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationLabel = 'Issue Alerts';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $title = 'Department Alerts';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.department-issue-alert-dashboard';

    protected function getTableQuery(): Builder
    {
        return Issue_report::query()
            ->where('responsible_dept_id', Auth::user()->dept_id)
            ->whereNotIn('status', ['pending_review', 'closed'])
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('report_id')
                ->label('Issue ID')
                ->searchable(),
            TextColumn::make('issue_desc')
                ->label('Issue Desc'),
            TextColumn::make('created_by')
                ->label('Created By'),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'new' => 'primary',
                    'reported' => 'info',
                    'in_progress' => 'warning',
                    'pending_review' => 'success',
                    'dismissed' => 'danger',
                    'reopened' => 'warning',
                    'closed' => 'gray',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'new' => 'new',
                    'reported' => 'Reported',
                    'in_progress' => 'In Progress',
                    'pending_review' => 'Pending review',
                    'dismissed' => 'Dismissed',
                    'reopened' => 'Reopened',
                    'closed' => 'Closed',
                    default => ucfirst($state),
                }),
            TextColumn::make('created_at')
                ->label('Created At')
                ->timezone('Asia/Bangkok')
                ->dateTime('d/m/Y H:i'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'reported' => 'Reported',
                    'in_progress' => 'In progress',
                    'pending_review' => 'Pending review',
                    'closed' => 'Closed',
                    'reopened' => 'Reopened',
                ])
                ->searchable(),
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
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->url(fn ($record) => route('filament.admin.resources.issue-reports.view', ['record' => $record]))
            ->openUrlInNewTab(),

            Action::make('accept')
            ->label('Accept')
            ->icon('heroicon-o-check')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (Issue_report $record) => $record->status === 'reported')
            ->action(function (Issue_report $record) {

                // อัปเดตสถานะ
                $record->update(['status' => 'in_progress']);
                Problem::where('prob_id', $record->prob_id)->update(['status' => 'in_progress']);

                // ดึงข้อมูลปัญหา
                $problem = Problem::where('prob_id', $record->prob_id)->first();

                // แจ้งไปยังผู้สร้างปัญหา (employee)
                $employee = User::where('emp_id', $problem->emp_id)->first();

                if ($employee) {
                    Notification::make()
                        ->color('success')
                        ->icon('heroicon-o-document-check')
                        ->title('Issue report accepted')
                        ->body("Your issue Problem ID: {$record->prob_id} has been sent to the department: {$record->responsibleDept->dept_name}.")
                        ->sendToDatabase($employee);
                }

                // แจ้งไปยังผู้มี role = safety
                User::where('role', 'safety')->get()
                    ->each(fn ($user) =>
                        Notification::make()
                            ->color('success')
                            ->icon('heroicon-o-document-check')
                            ->title('Issue report accepted')
                            ->body("Issue report for Problem ID: {$record->prob_id} has been accepted.")
                            ->sendToDatabase($user)
                    );

                // Redirect ไปสร้าง Issue Response
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
        if (Auth::user()?->role !== 'department') {
            return null;
        }

        // นับเฉพาะที่เป็น reported และแผนกตรงกับผู้ใช้
        return \App\Models\Issue_report::where('status', 'reported')
            ->where('responsible_dept_id', Auth::user()->dept_id)
            ->count();
    }

    public static function canAccess(): bool
    {
         $user = Auth::user();
        return $user?->role === 'department';
    }
}
?>
