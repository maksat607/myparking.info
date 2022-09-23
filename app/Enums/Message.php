<?php

namespace App\Enums;

use App\Models\User;
use App\Models\Status;
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
    public $status = null;
    public static $superAdmin = 2;
    public static $messages = [


        'pendingShort' => "Заявка на хранение для авто car_title",
        'pendingLong' => "DATE в TIME. Заявка на хранение для авто car_title (VIN vin_number). Создал user_role user_email",

        'storageShort' => "Авто car_title прибыло на хранение",
        'storageLong' => "DATE в DATE авто car_title (VIN vin_number) прибыло на хранение. Принял user_role user_email.",

        'issuanceShort' => "Заявка на выдачу для авто car_title",
        'issuanceLong' => "DATE в TIME. Заявка на выдачу для авто car_title (VIN vin_number). Создал user_role user_email",

        'issuedShort' => 'Авто car_title снято с хранения',
        'issuedLong' => "DATE в TIME авто car_title (VIN vin_number) снято с хранения. Выдал user_role user_email.",

        'denied-for-storageShort' => 'Авто car_title отклонено на хранение',
        'denied-for-storageLong' => "DATE в TIME авто car_title (VIN vin_number) отклонено на хранение. Отклонил user_role user_email",

        'cancelled-by-partnerShort' => 'Авто car_title отклонено на хранение',
        'cancelled-by-partnerLong' => "DATE в TIME авто car_title (VIN vin_number) отклонено на хранение партнером. Отклонил user_role user_email",

        'cancelled-by-usShort' => 'Авто car_title отклонено на хранение',
        'cancelled-by-usLong' => "DATE в TIME авто car_title (VIN vin_number) отклонено на хранение нами. Отклонил user_role user_email",

        'cancelled-byShort' => 'Авто car_title отклонено на хранение нами',
        'cancelled-byLong' => "DATE в TIME авто car_title (VIN vin_number) отклонено на хранение нами. Отклонил user_role user_email",

        'deletedShort' => 'Авто car_title удалено',
        'deletedLong' => "DATE в TIME авто car_title (VIN vin_number) удалено. Удалено user_role user_email",


        'viewRequestShort1' => "Заявка на осмотр авто car_title",
        'viewRequestLong1' => "DATE в TIME. Заявка на осмотр для авто car_title (VIN vin_number). Создал user_role user_email",

        'viewRequestShort2' => 'Авто car_title осмотрено',
        'viewRequestLong2' => "DATE в TIME. Авто car_title (VIN vin_number) осмотрено. user_role user_email",

        'viewRequestShort3' => 'Авто car_title не осмотрено',
        'viewRequestLong3' => "DATE в DATE. Авто car_title (VIN vin_number) не осмотрено.",
    ];

    public static function getViewRequestMessage($item, $user){
        $messages = Message::generateMessages($item->application, $user);

        return [
            'short' => $messages['viewRequestShort'.$item->status_id],
            'long' => $messages['viewRequestLong'.$item->status_id],
            'id'=>$item->application_id
        ];
    }
    public static function getApplicationMessage($item, $user)
    {
        $messages = Message::generateMessages($item, $user);
        $status = Status::find($item->status_id);
        return [
            'short' => $messages[$status->code . 'Short'],
            'long' => $messages[$status->code . 'Long'],
            'id'=>$item->id
        ];
    }

    public static function parseMessage($messageSearchReplace)
    {
        $arr = [];
        foreach (self::$messages as $key => $message) {
            $arr[$key] = str_replace(array_keys($messageSearchReplace), array_values($messageSearchReplace), $message);
        }
        return $arr;
    }

    public static function generateMessages($item, $user)
    {
        $messages = [
            'car_title' => $item->car_title,
            'vin_number' => $item->vin ?? $item->license_plate,
            'VIN'=>$item->vin ? 'VIN' : 'Гос.номер ',
            'user_email'=>$user->email,
            'user_role' => $user->getRoleNames()->first(),
            'DATE' => Carbon::now()->format('d.m.Y'),
            'TIME' => Carbon::now()->format('H:m'),
        ];
        $arr = self::parseMessage($messages);
        $arr['updated_at'] = $item->updated_at;
        $arr['arrived_at'] = $item->arrived_at;
        return $arr;
    }

    public static function getPermission($status, $item)
    {
        $deniedStatuses = [4, 5, 6];
        if (in_array($status->id, $deniedStatuses)) {
            return 'notify_app_denied';
        }
        if ($item->issuance) {
            return 'notify_app_issuance';
        }
        return 'notify_app_' . $status->code;
    }

    public static function getUsers($item)
    {
        $status = Status::find($item->status_id);
        $users = collect([]);
        if ($item->partner && $item->partner->user) {
            $users = $item->partner->user->children;
            $users->push($item->partner->user);
        }
        if ($item->createdUser->owner) {
            $users = $item->createdUser->owner->children;
            $users->push($item->createdUser->owner);
        } else {
            $users->push($item->createdUser);
        }
        $users = $users->push(User::find(self::$superAdmin));
        $users = $users->unique('id')->all();

        $permission = self::getPermission($status, $item);
        $users = collect(array_filter($users))->reject(function ($user) use ($permission) {
            return !$user->hasPermissionTo($permission);
        });
        return $users;
    }
}
