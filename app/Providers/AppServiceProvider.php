<?php

namespace App\Providers;

use App\Models\User;
use App\View\Composers\ApplicationFilterComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
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
        if(env('APP_ENV')=='prod'){
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }
        Paginator::useBootstrap();
//        Paginator::useBootstrapThree();
        View::composer(
            [
                'applications.index',
                'applications.index_table',
                'applications.index_row',
                'applications.accepting',
                'view_request.index',
                'issue_request.index',
            ],
            ApplicationFilterComposer::class
        );

        
    }
}
