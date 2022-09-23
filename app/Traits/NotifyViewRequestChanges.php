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
            $data = Message::getViewRequestMessage($item, auth()->user());

            if (count($data) > 0) {
                Notification::send(Message::getUsers($item->application), new UserNotification(($data)));
            }


        });

        static::deleted(function ($item) {

            $data = Message::getViewRequestMessage($item, auth()->user());

            if (count($data) > 0) {
                Notification::send(Message::getUsers($item->application), new UserNotification(($data)));
            }
        });
        static::created(function ($item) {
            $data = [];
            $data = Message::getViewRequestMessage($item, auth()->user());

            if (count($data) > 0) {
                Notification::send(Message::getUsers($item->application), new UserNotification(($data)));
            }

        });
    }
}
