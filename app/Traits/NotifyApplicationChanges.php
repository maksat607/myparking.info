<?php

namespace App\Traits;
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
            $messages = Message::generateMessages($item);
            if ($item->status->id != $item->status_id) {
                $date = [];
                if ($item->status_id == Message::$appStatuses['Хранение']) {
                    $data = [
                        'short' => $messages['acceptedForStorageShort'],
                        'long' => $messages['acceptedForStorageLong'],
                    ];
                }
                if ($item->status_id == Message::$appStatuses['Ожидает принятия']) {
                    $data = [
                        'short' => $messages['applyForStorageShort'],
                        'long' => $messages['applyForStorageLong'],
                    ];
                }
                if ($item->status_id == Message::$appStatuses['Выдано']) {
                    $data = [
                        'short' => $messages['issuedFromStorageShort'],
                        'long' => $messages['issuedFromStorageLong'],
                    ];
                }
                if ($item->status_id == Message::$appStatuses['Отклонена в хранении']) {
                    $data = [
                        'short' => $messages['rejectedForStorageShort'],
                        'long' => $messages['rejectedForStorageLong'],
                    ];
                }
                if (count($data) > 0) {
                    Notification::send(Message::getUsers($item), new UserNotification(($data)));
                }
            }
        });
        static::deleted(function ($item) {

            Log::info('Deleted   event call: ' . $item);
        });
        static::created(function ($item) {
            $data = [];
            $messages = Message::generateMessages($item);
            if ($item->status_id == Message::$appStatuses['Ожидает принятия']) {
                $data = [
                    'short' => $messages['applyForStorageShort'],
                    'long' => $messages['applyForStorageLong'],
                ];
            }
            if (!isset($item['status']) && $item->status_id == Message::$appStatuses['Хранение']) {
                $data = [
                    'short' => $messages['acceptedForStorageShort'],
                    'long' => $messages['acceptedForStorageLong'],
                ];
            }
            if (count($data) > 0) {
                Notification::send(Message::getUsers($item), new UserNotification(($data)));
            }
            Log::info('Created event call: ' . $item);
        });
    }
}
