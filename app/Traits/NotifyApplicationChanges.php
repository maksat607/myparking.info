<?php

namespace App\Traits;

use App\Models\Application;
use App\Models\ApplicationHasPending;
use App\Notifications\UserNotification;
use App\Enums\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

trait NotifyApplicationChanges
{
    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($item) {

        });
        static::updated(function ($item) {
            if ($item->status->id != $item->status_id) {
                $data = [];
                if (($item->status_id == Message::$appStatuses['Выдано'] || $item->status_id == Message::$appStatuses['Хранение'] || ($item->issuance)) && !auth()->user()->hasRole('Moderator')) {
                    ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                }
                $data = Message::getApplicationMessage($item, auth()->user());
                if (count($data) > 0) {
                    Notification::send(Message::getUsers($item), new UserNotification(($data)));
                }
                Log::info('Created event call: ' . $item);
            }
        });
        static::deleted(function ($item) {
            Log::info('Deleted   event call: ' . $item);
        });
        static::created(function ($item) {
            $data = [];
            if ($item->status_id == Message::$appStatuses['Ожидает принятия'] || $item->status_id == Message::$appStatuses['Хранение']) {
                $data = Message::getApplicationMessage($item, auth()->user());
            }
            if (count($data) > 0) {
                Notification::send(Message::getUsers($item), new UserNotification(($data)));
            }
            Log::info('Created event call: ' . $item);
        });
    }
}