<?php

namespace App\Http\Middleware;

use App\Notifications\TelegramNotification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        Log::channel('requests')->notice(['user'=>optional($request->user())->email,'request' => $request->all(), 'url' => $request->url()]);

        return $next($request);
    }
//    public function terminate($request, $response)
//    {
//
//        TelegramNotification::sendMessage(json_encode(['request' => $request->all(), 'url' => $request->url()], JSON_UNESCAPED_SLASHES));
//
//    }

}
