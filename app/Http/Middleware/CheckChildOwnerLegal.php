<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckChildOwnerLegal
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

        if (auth()->user()->owner && auth()->user()->owner->legals()->doesntExist() && auth()->user()->hasRole(['Moderator', 'Operator'])) {
            return redirect()->route('home')
                ->with('warning',
                    __('Your Administrator did not create more than one Legal Entity. You can\'t continue working.'));
        }

        return $next($request);
    }
}
