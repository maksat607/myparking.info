<?php

namespace App\Traits;

use App\Models\Application;
use App\Models\ApplicationHasPending;
use App\Notifications\ApplicationNotifications;
use App\Notifications\UserNotification;
use App\Enums\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

trait NotifyApplicationChanges
{
    public static $appStatuses = [
        'Черновик' => 1,
        'Хранение' => 2,
        'Выдано' => 3,
        'Отклонена в хранении' => 4,
        'Ожидает принятия' => 7,
        'Удален' => 8,
        'Модерация' => 9,
    ];

    public static function boot()
    {

        parent::boot();


        static::retrieved(function ($item) {
        });
        static::saving(function ($item) {
            $item->vin = $item->vin == "не указан" ? null : $item->vin;
            $item->license_plate = $item->license_plate == "не указан" ? null : $item->license_plate;

            if (!$item->ApplicationHasPending && $item->status_id == self::$appStatuses['Хранение'] && $item->partner->moderation == 1 && isset($item['id'])) {
                ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                $item->status_id = self::$appStatuses['Модерация'];
                $item->arrived_at = null;
            }
        });
        static::updated(function ($item) {

            if (($item->status->id != $item->status_id)) {
                $data = [];

                $message = new Message($item);
                $data = $message->applicationMessage;
                if (count($data) > 0) {
                    Notification::send($message->users, new UserNotification($data));
                }
            }
        });
        static::deleted(function ($item) {
        });
        static::created(function ($item) {
            $message = new Message($item);
            $data = [];
            if ($item->status_id == self::$appStatuses['Хранение'] && $item->partner->moderation == 1 ) {
                ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                $item->status_id = self::$appStatuses['Модерация'];
                $item->save();
            }
            if ($item->status_id == self::$appStatuses['Ожидает принятия'] || $item->status_id == self::$appStatuses['Хранение']) {
                $data = $message->applicationMessage;
            }
            if (count($data) > 0) {
                Notification::send($message->users, new UserNotification(($data)));
            }
        });
    }
}
