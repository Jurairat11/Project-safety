<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssueResponsesResource\Pages;
use App\Models\Issue_responses; // Ensure this model exists in the specified namespace
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use App\Models\Issue_report;
use Illuminate\Support\Facades\Auth;

class IssueResponsesResource extends Resource
{
    protected static ?string $model = Issue_responses::class;

    protected static ?string $navigationLabel = 'Issue Responses';

    protected static ?string $pluralLabel = 'P-CAR Responses';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Section::make('Issue Responses')
                ->schema([
                    Forms\Components\Select::make('report_id')
                        ->label('Report ID')
                        ->relationship('issue_report', 'report_id')
                        ->options(function () {
                            $deptId = Auth::user()?->dept_id;
                            return \App\Models\Issue_report::where('responsible_dept_id', $deptId)
                                ->pluck('report_id', 'report_id');
                        })
                        ->default(fn () => request()->get('report_id')) // รับจาก URL
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $issue = \App\Models\Issue_report::find($state);
                            if ($issue) {
                                $set('safety_emp_id', $issue->created_by);
                                $set('form_no', $issue->form_no);
                            }
                        })
                        ->afterStateHydrated(function (callable $set, $state) {
                            $issue = \App\Models\Issue_report::find($state);
                            if ($issue) {
                                $set('safety_emp_id', $issue->created_by);
                                $set('form_no', $issue->form_no);
                            }
                        })
                        ->required(),

                    Forms\Components\TextInput::make('safety_emp_id')
                        ->label('Safety Officer ID')
                        ->disabled()
                        ->dehydrated(true) // ต้อง dehydrated เพื่อเก็บค่าลง database
                        ->required(),

                    Forms\Components\TextInput::make('form_no')
                        ->label('CAR No.')
                        ->disabled()
                        ->dehydrated(true)
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
                        ->hidden()// ซ่อน field
                        ->required(),

                Forms\Components\Section::make('Issue Solving')
                ->schema([
                        Forms\Components\Textarea::make('cause')
                            ->label('Cause')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\FileUpload::make('img_after')
                            ->label('Picture After')
                            ->directory('form-attachments')
                            ->visibility('public')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('temporary_act')
                            ->label('Temporary Action')
                            ->default('-'),

                        Forms\Components\Textarea::make('permanent_act')
                            ->label('Permanent Action')
                            ->default('-'),

                        Forms\Components\DatePicker::make('temp_due_date')
                            ->label('Due date')
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Forms\Components\DatePicker::make('perm_due_date')
                            ->label('Due date')
                            ->native(false)
                            ->displayFormat('d/m/y'),

                        Forms\Components\Select::make('temp_responsible')
                            ->label('Responsible Person')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->placeholder("Select responsible person"),

                        Forms\Components\Select::make('perm_responsible')
                            ->label('Responsible Person')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->placeholder("Select responsible person"),

                        Forms\Components\Textarea::make('preventive_act')
                            ->label('Preventive Action')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('remark')
                            ->label('Remark')
                            ->placeholder('Add reason if not resolved...')
                            ->rows(3)
                            ->dehydrated(true),

                        Forms\Components\Select::make('created_by')
                            ->label('Created by')
                            ->options(function () {
                                return \App\Models\Employees::all()->mapWithKeys(function ($employee) {
                                    return [$employee->emp_id => $employee->full_name];
                                });
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $query) {
                                return \App\Models\Employees::where('emp_id', 'like', "%{$query}%")
                                    ->orWhere('full_name', 'like', "%{$query}%")
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->emp_id => $employee->full_name];
                                    });
                            })
                            ->required()
                            ->default(function () {
                                $user = Auth::user();
                                return $user?->emp_id;
                            })
                            ->placeholder("Select create person"),


                    ]) ->columns(2)
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('response_id')
                    ->label('Response ID')
                    ->searchable('response_id'),
                Tables\Columns\TextColumn::make('safety_emp_id')
                    ->label('Assign By')
                    ->searchable('safety_emp_id')
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Employees::where('emp_id', $state)->first()?->full_name ?? $state;
                    }),
                Tables\Columns\ImageColumn::make('img_after')
                    ->label('Picture After'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucfirst($state)))
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'primary',
                        'reported' => 'info',
                        'in_progress' =>'warning',
                        'pending_review'=> 'success',
                        'reopened' => 'warning',
                        'dismissed' =>'danger',
                        'closed' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_by')
                    ->label('Created By')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Employees::where('emp_id', $state)->first()?->full_name ?? $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->timezone('Asia/Bangkok')
                    ->dateTime('d/m/Y H:i'),

            ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
            ->label('Status')
            ->options([
                'in_progress' => 'In progress',
                'pending_review' => 'Pending review',
                'closed' => 'Closed',
                'reopened' => 'Reopened',
            ])
            ->searchable(),
        ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListIssueResponses::route('/'),
            'create' => Pages\CreateIssueResponses::route('/create'),
            'view' => Pages\ViewIssueResponses::route('/{record}'),
            'edit' => Pages\EditIssueResponses::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
    $user = Auth::user();
        return in_array($user?->role, ['admin', 'department', 'safety']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return $user?->role !== 'employee';
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return in_array($user?->role, ['admin', 'department']);
    }

    public static function canEdit($record): bool
    {
        $user = Auth::user();
        return in_array($user?->role, ['admin', 'department']);
    }

    public static function canDelete($record): bool
    {
        $user = Auth::user();
        return in_array($user?->role, ['admin', 'department']);
    }

}
