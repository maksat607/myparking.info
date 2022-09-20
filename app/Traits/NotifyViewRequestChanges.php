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
            $messages = self::generateMessages($item);

        });
        static::deleted(function ($item) {

            Log::info('Deleted   event call: ' . $item);
        });
        static::created(function ($item) {
            $data = [];
            $messages = self::generateMessages($item);
            if ($item->status_id == self::$statuses['Ожидает принятия']) {
                $data = [
                    'short' => $messages['applyForStorageShort'],
                    'long' => $messages['applyForStorageLong'],
                ];
            }
            if (!isset($item['status']) && $item->status_id == self::$statuses['Хранение']) {
                $data = [
                    'short' => $messages['acceptedForStorageShort'],
                    'long' => $messages['acceptedForStorageLong'],
                ];
            }
            if (count($data) > 0) {
                Notification::send(self::getUsers($item), new UserNotification(($data)));
            }
            Log::info('Created event call: ' . $item);
        });
    }
}
