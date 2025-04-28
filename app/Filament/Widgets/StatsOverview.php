<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use APP\Models\Problem;
use Illuminate\Support\HtmlString;


class StatsOverview extends BaseWidget
{
    public int $totalProblems;
    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Problem', $this->totalProblems = Problem::count()),
            Stat::make('New', Problem::where('status','new')->count())
            ->description('New problem.')
            ->color('primary'),
            Stat::make('Reported', Problem::where('status','reported')->count())
            ->description('P-CAR sent.')
            ->color('info'),
            Stat::make('In progress', Problem::where('status','in_progress')->count())
            ->description('P-CAR accepted.')
            ->color('warning'),
            Stat::make('Pending review', Problem::where('status','pending_review')->count())
            ->description('P-CAR submitted.')
            ->color('success'),
            Stat::make('Dismissed', Problem::where('status','dismissed')->count())
            ->description('Problem dismissed.')
            ->color('danger'),
            Stat::make('Closed', Problem::where('status','closed')->count())
            ->description('P-CAR completed.')
            ->color('gray'),
            Stat::make( 'Reopened', Problem::where('status','reopened')->count())
            ->description('P-CAR unsolved.')
            ->color('warning'),
        ];
    }

}
//->label(new HtmlString('<span class="text-primary-400">Reported</span>')),
