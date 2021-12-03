<?php

namespace App\Providers;

use App\Export\CsvExport;
use App\Export\WordExport;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ReportController;
use App\Interfaces\ExportInterface;
use Illuminate\Support\ServiceProvider;

class ExportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app
            ->when(ApplicationController::class)
            ->needs(ExportInterface::class)
            ->give(function () {
                return new WordExport;
            });

        $this->app
            ->when(ReportController::class)
            ->needs(ExportInterface::class)
            ->give(function () {
                return new CsvExport;
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
