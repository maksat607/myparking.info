<?php

namespace App\Providers;

use App\Enums\AppUsers;
use App\Helpers\HideUser;
use App\Models\Application;
use App\Models\Role;
use App\Models\User;
use App\Notifications\TelegramNotification;
use App\View\Composers\ApplicationFilterComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HideUser::class, function () {
            return new HideUser();
        });
        $this->app->singleton(TelegramNotification::class, function () {
            return new TelegramNotification();
        });

        $this->app->singleton(FilterService::class, function () {
            return new FilterService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (env('APP_ENV') == 'prod') {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
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

        Validator::extend('unique_custom', function ($attribute, $value, $parameters) {
            list($table, $field) = $parameters;
            return DB::table($table)
                    ->where($field, $value)
                    ->where($field, '!=', 'не указан')
                    ->where('status_id', '!=', 8)->count() == 0;
        });


        Validator::extend('unique_custom_ignore', function ($attribute, $value, $parameters) {
            list($table, $field, $ignore) = $parameters;
            return DB::table($table)
                    ->where($field, $value)
                    ->where($field, '!=', 'не указан')
                    ->where('status_id', '!=', 8)
                    ->where('id', '!=', $ignore)->count() == 0;
        });


    }
}
