<?php

namespace App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TelegramNotification;

class LogEventListener
{
    public $user = null;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->level == 'error') {

            $parseError = $event->context['exception'];

            $message = [
                'project' => env('APP_NAME'),
                'message' => $parseError->getMessage(),
                'file' => $parseError->getFile(),
                'line' => $parseError->getLine()
            ];
//            TelegramNotification::sendMessage(json_encode($message, JSON_UNESCAPED_SLASHES));
//            AppUsers::getUsers()
        }
    }
}
