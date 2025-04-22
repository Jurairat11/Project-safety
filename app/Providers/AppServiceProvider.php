<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use App\Models\Problem;
use App\Observers\ProblemObserver;
use App\Models\Issue_report;
use App\Observers\Issue_reportObserver;

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

        Problem::observe(ProblemObserver::class);
        Issue_report::observe(Issue_reportObserver::class);
    }
}

