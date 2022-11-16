<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Http;

class TelegramNotification
{
    private $code;

    public function __construct()
    {
        $this->code = env('ERROR_LOG_TELEGRAM_CODE');
    }

    public static function sendMessage($message)
    {
        $telegram = app(TelegramNotification::class);
        $telegram->send($message);
        return $telegram;
    }

    public function send($message)
    {
        $data = ["companycode" => $this->code, "data" => [["message" => $message]]];
//        Http::post('https://t.kuleshov.studio/api/getmessages', $data);
    }

}
