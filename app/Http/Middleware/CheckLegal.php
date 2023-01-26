<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLegal
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

        if (auth()->user()->legals->isEmpty() && auth()->user()->hasRole(['Admin'])) {
            return redirect()->route('legals.index')
                ->with('warning',
                    __('You don\'t have a single Legal entity. Please create at least one Legal Entity to continue using the service.'));

        }

        return $next($request);
    }
}
