<?php

namespace App\Enums;

use App\Models\User;
use Carbon\Carbon;

class Message
{
    public static $appStatuses = [
        'Черновик' => 1,
        'Хранение' => 2,
        'Выдано' => 3,
        'Отклонена в хранении' => 4,
        'Ожидает принятия' => 7,
        'Удален' => 8,
    ];
    public static $ViewRequestStatuses = [
        'В ожидании' => 1,
        'Осмотрено' => 2,
        'Не осмотрено' => 3,
    ];

    public static $superAdmin = 2;
    public static $messages = [
        'acceptedForStorageShort' => "Авто car_title прибыло на хранение",
        'diffForHumans' => "diffForHumans",
        'applyForStorageShort' => "Заявка на хранение для авто car_title",
        'applyForStorageLong' => "DATE в TIME. Заявка на хранение для авто car_title (VIN vin_number). Создал менеджер created_user",
        'acceptedForStorageLong' => "accepted_at_date в accepted_time авто car_title (VIN vin_number) прибыло на хранение. Принял менеджер accepted_by.",
        'issuedFromStorageShort' => 'Авто car_title снято с хранения',
        'issuedFromStorageLong' => "issued_at_date в issued_time авто car_title (VIN vin_number) снято с хранения. Выдал менеджер issued_by.",
        'rejectedForStorageShort' => 'Авто car_title отклонено на хранение',
        'rejectedForStorageLong' => "rejected_at_date в rejected_time авто car_title (VIN vin_number) отклонено на хранение. Отклонил менеджер rejected_by",
        'requestViewPendingShort' => "Заявка на осмотр авто car_title",
        'requestViewPendingLong' => "DATE в TIME. Заявка на осмотр для авто car_title (VIN vin_number). Создал менеджер created_user",
        'requestViewedShort' => 'Авто car_title осмотрено',
        'requestViewedLong' => "DATE в TIME. Авто car_title (VIN vin_number) осмотрено. Осмотр провел менеджер created_user",
        'requestViewFailedShort' => 'Авто car_title не осмотрено',
        'requestViewFailedLong' => "DATE в TIME. Авто car_title (VIN vin_number) не осмотрено.",
    ];
    public static function parseMessage($messageSearchReplace)
    {
        $arr = [];
        foreach (self::$messages as $key => $message) {
            $arr[$key] = str_replace(array_keys($messageSearchReplace), array_values($messageSearchReplace), $message);
        }
        return $arr;
    }

    public static function generateMessages($item)
    {
        $user = $item->acceptedBy;
        $acceptedUserEmail = optional($user)->email;
        $user = $item->removedBy;
        $issuedUserEmail = optional($user)->email;
        $messages = [
            'car_title' => $item->car_title,
            'vin_number' => $item->vin,
            'accepted_at_date' => optional($item->arrived_at)->format('d.m.Y'),
            'accepted_time' => optional($item->arrived_at)->format('H:m'),
            'accepted_by' => $acceptedUserEmail,
            'rejected_at_date' => optional($item->updated_at)->format('d.m.Y'),
            'rejected_time' => optional($item->updated_at)->format('H:m'),
            'issued_at_date' => optional($item->issued_at)->format('d.m.Y'),
            'issued_time' => optional($item->issued_at)->format('H:m'),
            'issued_by' => $issuedUserEmail,
            'created_user' => optional($item->createdUser)->email,
            'deleted_by' => optional($item->deletedBy)->email,
            'rejected_by' => optional($item->rejectedBy)->email,
            'DATE' => Carbon::now()->format('d.m.Y'),
            'TIME' => Carbon::now()->format('H:m'),
        ];
        $arr = self::parseMessage($messages);
        $arr['updated_at'] = $item->updated_at;
        $arr['arrived_at'] = $item->arrived_at;
        return $arr;
    }

    public static function getUsers($item)
    {

        $users = collect([]);
        if ($item->partner) {
            $users = $item->partner->user->children;
            $users->push($item->partner->user);
        }
//        $users = $users->push(User::find(self::$superAdmin));
        return $users;
    }
}
