<?php

namespace App\Traits;

use App\Enums\Message;
use App\Models\ApplicationHasPending;
use App\Notifications\UserNotification;
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
            $item->vin = mb_strtoupper($item->vin) == mb_strtoupper("не указан") ? null : mb_strtoupper($item->vin);
            $item->license_plate = mb_strtoupper($item->license_plate) == mb_strtoupper("не указан") ? null : mb_strtoupper($item->license_plate);

//            dump($item->status->id);
//            dd($item->status_id);
            if (
                !$item->ApplicationHasPending &&
                $item->status_id == self::$appStatuses['Хранение'] &&
                $item->partner->moderation == 1 &&
                isset($item['id']) &&
                ($item->status->id != $item->status_id) ||
                $item->status_id == self::$appStatuses['Модерация']
            ) {
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
            if ($item->status_id == self::$appStatuses['Хранение'] && $item->partner->moderation == 1) {
                ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                $item->status_id = self::$appStatuses['Модерация'];
                $item->arrived_at = null;
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
