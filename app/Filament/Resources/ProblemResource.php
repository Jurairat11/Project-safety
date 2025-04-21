<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProblemResource\Pages;
use App\Filament\Resources\ProblemResource\RelationManagers;
use App\Models\Problem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use App\Models\Employees;
use App\Models\Issue_report;
use App\Models\User;
use App\Notifications\NewProblemReported;


class ProblemResource extends Resource
{
    protected static ?string $model = Problem::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('emp_id')
                    ->label('Reported by')
                    ->options(fn () => \App\Models\Employees::all()->pluck('full_name', 'emp_id'))
                    ->default(auth()->user()?->emp_id),


                Select::make('dept_id')
                    ->label('Department')
                    ->options(fn () => \App\Models\Dept::all()->pluck('dept_name', 'dept_id'))
                    ->default(fn () => auth()->user()?->dept_id),

                Textarea::make('prob_desc')
                    ->label('Description')
                    ->required(),

                FileUpload::make('pic_before')
                    ->label('Picture')
                    ->image()
                    ->directory('form-attachments')
                    ->visibility('public'),

                TextInput::make('location')
                    ->label('Location')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'new',
                        'reported' => 'reported',
                        'in_progress' => 'in progress',
                        'pending_review' => 'pending review',
                        'closed' => 'closed',
                        'reopened' => 'reopened',
                        'dismissed' => 'dismissed',
                    ])
                    ->default('new')
                    ->disabled() // หากให้ระบบเปลี่ยนสถานะเอง
                    ->dehydrated(false), // ไม่ส่งกลับถ้า disabled

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('prob_id')
                    ->label('Problem ID'),
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Reported by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dept.dept_name')
                    ->label('Department'),
                Tables\Columns\ImageColumn::make('pic_before')
                    ->label('Picture'),
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
                        'reopened'=> 'warning',
                        'closed' => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->sortable()
                    ->dateTime('d/m/Y'),


            ])
            ->filters([
                //
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
            'index' => Pages\ListProblems::route('/'),
            'create' => Pages\CreateProblem::route('/create'),
            'edit' => Pages\EditProblem::route('/{record}/edit'),
            'view' => Pages\ViewProblem::route('/{record}')
        ];
    }

    public static function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    public static function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->role, ['employee','safety', 'admin']);
    }


    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'employee';
    }





}
