<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssueReportResource\Pages;
use App\Models\Employees;
use App\Models\Issue_report;
use Illuminate\Support\Carbon;
use App\Models\Section;
use App\Models\Dept;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class IssueReportResource extends Resource
{
    protected static ?string $model = Issue_report::class;
    protected static ?string $navigationGroup = 'Issue Report';
    protected static ?string $navigationLabel = 'Issue Report';
    protected static ?string $pluralLabel = 'P-CAR Reports';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    public static function getNavigationBadge(): ?string
    {
        $count = Issue_report::where('status', 'pending_review')->count();

        return $count > 0 ? (string) $count : null;
    }

    protected static ?string $navigationBadgeColor = 'warning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Problem Report')
                    ->label('Issue Details')
                    ->schema([
                        Forms\Components\Select::make('prob_id')
                        ->label('Problem ID')
                        ->relationship('problem', 'prob_id')
                        ->options(function () {
                            // 1. จาก Problem โดยตรง
                            $fromProblem = \App\Models\Problem::where('status', 'new')->pluck('prob_id')->toArray();

                            // 2. จาก Issue_report ที่ status = reopened → ดึง prob_id
                            $fromIssueReports = \App\Models\Issue_report::where('status', 'reopened')
                                ->pluck('prob_id')
                                ->toArray();

                            // รวมและกรอง prob_id ไม่ซ้ำ
                            $probIds = collect(array_merge($fromProblem, $fromIssueReports))->unique();

                            // ดึงข้อมูล prob_id → สำหรับ select
                            return \App\Models\Problem::whereIn('prob_id', $probIds)->pluck('prob_id', 'prob_id');
                        })
                        ->default(fn () => request()->get('prob_id'))
                        ->reactive()
                        ->dehydrated(true)
                        ->afterStateHydrated(function ($state, callable $set) {
                            if ($state) {
                                $problem = \App\Models\Problem::find($state);

                                if ($problem) {
                                    $set('prob_desc', $problem->prob_desc);
                                    $set('emp_id', $problem->emp_id);

                                    $employee = \App\Models\Employees::where('emp_id', $problem->emp_id)->first();
                                    if ($employee && $employee->dept_id) {
                                        $set('dept_id', $employee->dept_id);
                                    }
                                }
                            }
                        })
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $problem = \App\Models\Problem::find($state);

                                if ($problem) {
                                    $set('prob_desc', $problem->prob_desc);
                                    $set('emp_id', $problem->emp_id);

                                    $employee = \App\Models\Employees::where('emp_id', $problem->emp_id)->first();
                                    if ($employee && $employee->dept_id) {
                                        $set('dept_id', $employee->dept_id);
                                    }
                                }
                            }
                        }),

                            Forms\Components\TextInput::make('emp_id')
                                ->label('Reporting employee')
                                ->default(function () {
                                    $probId = request()->get('prob_id');
                                    if ($probId) {
                                        $problem = \App\Models\Problem::find($probId);
                                        return $problem?->emp_id;
                                    }
                                    return null;
                                })
                                ->disabled()
                                ->dehydrated(true),

                            Forms\Components\Select::make('dept_id')
                                ->label('Reporter Department')
                                ->options(fn () => \App\Models\Dept::pluck('dept_name', 'dept_id'))
                                ->default(function () {
                                    $probId = request()->get('prob_id');
                                    if ($probId) {
                                        $problem = \App\Models\Problem::find($probId);
                                        $emp = \App\Models\Employees::where('emp_id', $problem?->emp_id)->first();
                                        return $emp?->dept_id;
                                    }
                                    return null;
                                })
                                ->disabled()
                                ->dehydrated(true),

                            Forms\Components\Textarea::make('prob_desc')
                                ->label('Description')
                                ->default(function () {
                                    $probId = request()->get('prob_id');
                                    if ($probId) {
                                        $problem = \App\Models\Problem::find($probId);
                                        return $problem?->prob_desc;
                                    }
                                    return null;
                                })
                                ->disabled()
                                ->dehydrated(true),

                        ])->columns(2), // แสดงเป็น 2 คอลัมน์

                    Forms\Components\Section::make('P-CAR Details')
                    ->schema([

                        Forms\Components\TextInput::make('form_no')
                        ->label('Form No.')
                        ->default(function () {
                            $year = Carbon::now()->format('y'); // ปี ค.ศ. 2 หลัก เช่น 25
                            $count = Issue_report::whereYear('created_at', now()->year)->count() + 1;

                            return 'C ' . str_pad($count, 2, '0', STR_PAD_LEFT) . '/' . $year;
                        })
                        ->unique(ignoreRecord: true)
                        ->disabled()
                        ->dehydrated(true),

                        //แสดงแผนกของ จป.
                        Forms\Components\Select::make('safety_dept')
                            ->label('Safety Department')
                            ->options(Dept::pluck('dept_name', 'dept_id'))
                            ->reactive(),  // เมื่อเปลี่ยนแผนก จะ re-render ฟิล์ม

                        Forms\Components\Select::make('section')
                            ->label('Safety Section')
                            ->options(function (callable $get) {
                                $deptId = $get('safety_dept');
                                return Section::where('dept_id', $deptId)
                                            ->pluck('sec_name', 'sec_id');
                            })
                            ->required()
                            ->disabled(fn (callable $get) => ! $get('safety_dept'))
                            ->reactive(), // รองรับการโหลด option ใหม่

                        //วันที่แจ้ง
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Create Date')
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),

                        //วันที่สิ้นสุด
                        Forms\Components\DatePicker::make('dead_line')
                            ->label('Deadline Date')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),

                        Forms\Components\Textarea::make('issue_desc')
                            ->label('Issue Description')
                            ->placeholder('Describe the issue')
                            ->rows(4)
                            ->nullable(),

                        Forms\Components\Select::make('hazard_level_id')
                            ->label('Hazard Level')
                            ->relationship('hazardLevel','Level')
                            ->required(),

                        Forms\Components\Select::make('hazard_type_id')
                            ->label('Hazard Type')
                            ->relationship('hazardType', 'Desc')
                            ->required(),

                        Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'reported' => 'reported',
                            'in_progress' => 'in progress',
                            'pending_review' => 'pending review',
                            'reopened' => 'reopened',
                            'closed' =>'closed'
                        ])
                        ->default('reported')
                        ->hidden()// ซ่อน field
                        ->required(),

                    Forms\Components\FileUpload::make('img_before')
                        ->label('Picture Before')
                        ->directory('form-attachments')
                        ->visibility('public'),

                    Forms\Components\Select::make('responsible_dept_id')
                        ->label('Responsible Department')
                        ->options(Dept::pluck('dept_name', 'dept_id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('created_by')
                        ->label('Created By')
                        ->searchable()
                        ->options(
                            Employees::all()->pluck('full_name', 'emp_id') // key = emp_id, value = name
                        )
                        ->default(function () {
                            $user = Auth::user();
                            return $user?->emp_id;
                        })
                        ->required(),

                    Forms\Components\Hidden::make('parent_id')
                        ->dehydrated(true) // สำคัญมาก ให้บันทึกค่า
                        ->default(request()->get('parent_id')) // เผื่อ prefill จาก query string

                    ])->columns(3),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_id')
                    ->label('Report ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('problem.prob_id')
                    ->label('Problem ID'),
                /*Tables\Columns\ImageColumn::make('img_before')
                    ->label('Picture Before'),*/
                Tables\Columns\TextColumn::make('hazardLevel.Level')
                    ->label('Hazard Level')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hazardType.Desc')
                    ->label('Hazard Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucfirst($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'primary',
                        'reported' => 'info',
                        'in_progress' =>'warning',
                        'pending_review'=> 'success',
                        'dismissed' =>'danger',
                        'closed' => 'gray',
                        'reopened' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('responsibleDept.dept_name')
                    ->label('Responsible'),
                Tables\Columns\TextColumn::make('created_by')
                    ->label('Created By')
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Employees::where('emp_id', $state)->first()?->full_name ?? $state;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Bangkok')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options( [
                    'reported' => 'Reported',
                    'in_progress' => 'In progress',
                    'pending_review' => 'Pending review',
                    'closed' => 'Closed',
                    'reopened' => 'Reopened',
                ])
                ->searchable()
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIssueReports::route('/'),
            'create' => Pages\CreateIssueReport::route('/create'),
            'edit' => Pages\EditIssueReport::route('/{record}/edit'),
            'view' => Pages\ViewIssueReport::route('/{record}'),
        ];
    }

    public static function beforeCreate($data): void
    {
        $user = Auth::user();
        $data['created_by'] = Auth::user()?->emp_id;
    }
        public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user?->role !== 'employee';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return in_array($user?->role, ['admin','safety']);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return in_array ($user?->role, ['safety','admin']); ;
    }

    public static function canEdit($record): bool
    {
        $user = Auth::user();
        return in_array ($user?->role, ['safety','admin']) ;
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();
        return in_array ($user?->role, ['safety','admin']) ;
    }



}
