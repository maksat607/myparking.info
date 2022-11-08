<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Collection::macro('filterStatusesByRole', function () {
            return $this->filter(function ($value) {
                return true;
//                return auth()->user()->can('el_status_' . $value->code);
            });
        });
        Collection::macro('filterPermissions', function ($hasButtons) {
            $exists = [];
            $this->map(function ($item) use (&$exists, $hasButtons) {
                $exists[$item->name] = $item->permissions->reject(function ($item) use ($hasButtons) {
                    return $hasButtons == str_starts_with($item->name, 'el_');
                })->pluck('name')->toArray();
            });
            return $exists;
        });
//        Collection::macro('filterStatusByRole', function () {
//            return $this->filter(function ($value) {
//                return auth()->user()->can('el_'.$value->name);
//            });
//        });


        Request::macro('admin', function () {
            if (auth()->user()->hasRole('SuperAdmin')) {
                return 'Admin';
            }
            return false;
        });
    }
}
