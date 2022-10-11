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
    ];
    public static function boot()
    {

        parent::boot();


        static::retrieved(function ($item) {
//            $item->notify(new ApplicationNotifications(['name'=>'Maksat']));
//dd($item->notifications);
        });
        static::saving(function ($item) {
            Log::info('being updated: ' . ($item));
            $item->vin = $item->vin == "не указан" ? null : $item->vin;
            $item->license_plate = $item->license_plate == "не указан" ? null : $item->license_plate;
        });
        static::updated(function ($item) {

            if ($item->status->id != $item->status_id) {
                $data = [];
                if (($item->status_id == self::$appStatuses['Выдано'] || $item->status_id == self::$appStatuses['Хранение'] || ($item->issuance)) && !auth()->user()->hasRole('Moderator')) {
                    ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                }
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
            if ($item->status_id == self::$appStatuses['Ожидает принятия'] || $item->status_id == self::$appStatuses['Хранение']) {
                $data = $message->applicationMessage;
            }
            if (($item->status_id == self::$appStatuses['Выдано'] || $item->status_id == self::$appStatuses['Хранение'] || ($item->issuance)) && !auth()->user()->hasRole('Moderator')) {
                ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
            }
            if (count($data) > 0) {
                Notification::send($message->users, new UserNotification(($data)));
            }

        });
    }
//    public static create
}
