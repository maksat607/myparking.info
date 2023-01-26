<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Enums\Message;
trait NotifyViewRequestChanges
{

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($item) {

        });
        static::updated(function ($item) {
            $message = new Message(null,$item);
            $data = $message->applicationViewMessage;

            if (count($data) > 0) {
                Notification::send($message->users, new UserNotification(($data)));
            }


        });

        static::deleted(function ($item) {
            $message = new Message(null,$item);
            $data = $message->applicationViewMessage;

            if (count($data) > 0) {
                Notification::send($message->users, new UserNotification(($data)));
            }
        });
        static::created(function ($item) {
            $data = [];
            $message = new Message(null,$item);
            $data = $message->applicationViewMessage;

            if (count($data) > 0) {
                Notification::send($message->users, new UserNotification(($data)));
            }

        });
    }
}
