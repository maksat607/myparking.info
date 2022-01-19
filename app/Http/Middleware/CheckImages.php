<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class CheckImages
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guest()) return $next($request);

        /*dd((!$request->routeIs('applications.create') &&
            !$request->routeIs('applications.store')));*/

        if((!$request->routeIs('applications.create') &&
            !$request->routeIs('applications.store')) &&
            $request->session()->has('images') && !$request->ajax())
        {
            $request->session()->forget('images');
        }

        /*if () {
            return redirect()->route('legals.index')
                ->with('warning',
                    __('You don\'t have a single Legal entity. Please create at least one Legal Entity to continue using the service.'));

        }*/

        return $next($request);
    }
}
