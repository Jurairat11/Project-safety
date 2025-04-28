<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use App\Models\Problem;
use App\Observers\ProblemObserver;
use App\Models\Issue_report;
use App\Observers\Issue_reportObserver;
use App\Models\Issue_responses;
use App\Observers\Issue_responsesObserver;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            // หากอยากเพิ่มอย่างอื่น เช่น navigation group ทำได้ตรงนี้
        });

        FilamentColor::register([
            'red' => Color::Red,
            'gray' => Color::Gray,
            'blue' => Color::Blue,
            'indigo' => Color::Indigo,
            'green' => Color::Green,
            'yellow' => Color::Yellow,
        ]);

        Problem::observe(ProblemObserver::class);
        Issue_report::observe(Issue_reportObserver::class);
        Issue_responses::observe(Issue_responsesObserver::class);
    }
}

