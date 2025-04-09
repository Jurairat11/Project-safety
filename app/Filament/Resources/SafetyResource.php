<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SafetyResource\Pages;
use App\Filament\Resources\SafetyResource\RelationManagers;
use App\Models\Safety;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Problem;
use App\Models\HazardLevel;
use App\Models\HazardType;
use Filament\Forms\Components\Textarea;

class SafetyResource extends Resource
{
    protected static ?string $model = Safety::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Fieldset::make('prob_id')
                ->label('P-CAR Details')
                ->schema([
                    Forms\Components\Select::make('prob_id')
                        ->label('Problem ID')
                        ->relationship('problem','prob_id')
                        ->options(function () {
                            return Problem::all()->pluck('prob_id', 'prob_id');
                        })
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {

                            $problem = Problem::find($state);
                            if ($problem) {

                                $set('prob_desc', $problem->prob_desc);
                                $set('emp_id', $problem->emp_id);
                            }
                        })
                        ->required(),

                    Forms\Components\Placeholder::make('emp_id')
                        ->label('Reporting employee')
                        ->content(function ($state) {
                        return $state ? $state : '-';
                    }),

                    Forms\Components\Textarea::make('prob_desc')
                        ->label('Description')
                        ->default('-')
                        ->rows(5)
                        ->columnSpan(2)
                        ->maxLength(1000)
                        ->disabled(),

                    Forms\Components\Select::make('hazard_level_id')
                        ->label('Hazard Level')
                        ->relationship('hazardLevel', 'Level')
                        ->required(),

                    /*Forms\Components\Radio::make('hazard_level')
                        ->label('Hazard Level')
                        ->options(function () {
                            return \App\Models\HazardLevel::all()->mapWithKeys(function ($item) {
                                return [$item->hazard_level_id => "{$item->Level} : {$item->desc}"];
                            });
                        })
                        ->required(),*/

                    Forms\Components\Select::make('hazard_type_id')  // ใช้ hazard_type_id เป็นฟิลด์ในตาราง Safety
                        ->label('Hazard Type')
                        ->relationship('hazardType', 'Desc')  // เชื่อมโยงกับโมเดล HazardType และแสดงค่าจากฟิลด์ Desc
                        ->required(),


                    Forms\Components\FileUpload::make('pic_before')
                        ->label('Picture Before')
                        ->required(),

                    Forms\Components\FileUpload::make('pic_after')
                        ->label('Picture After')


                ]),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('safety_id')
                    ->label('Safety ID'),
                Tables\Columns\TextColumn::make('problem.prob_id')
                    ->label('Problem_id'),
                Tables\Columns\TextColumn::make('hazardLevel.Level')
                    ->label('Hazard Level')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hazardType.Desc')
                    ->label('Hazard Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('pic_before')
                    ->label('Picture Before'),
                Tables\Columns\ImageColumn::make('pic_after')
                    ->label('Picture After')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSafeties::route('/'),
            'create' => Pages\CreateSafety::route('/create'),
            'edit' => Pages\EditSafety::route('/{record}/edit'),
        ];
    }
}
