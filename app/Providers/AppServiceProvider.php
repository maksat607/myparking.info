<?php

namespace App\Providers;

use App\Models\User;
use App\View\Composers\ApplicationFilterComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            [
                'applications.index',
                'applications.index_status',
                'applications.accepting',
                'view_request.index',
                'issue_request.index',
            ],
            ApplicationFilterComposer::class
        );
    }
}
