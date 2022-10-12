<?php

namespace App\Enums;

use App\Models\User;
use App\Models\Status;
use Carbon\Carbon;

class Message
{
    public $users;
    private $user;
    private $application;
    private $applicationView = [];
    public $applicationMessage = [];
    public $applicationViewMessage;
    private $ViewRequestStatuses = [
        'В ожидании' => 1,
        'Осмотрено' => 2,
        'Не осмотрено' => 3,
    ];
    private $status = null;
    private $superAdmin = 2;
    private $messages = [


        'pendingShort' => "Заявка на хранение для авто car_title",
        'pendingLong' => "DATE в TIME... Заявка на хранение для авто car_title (VIN vin_number). Создал user_role user_email",

        'storageShort' => "Авто car_title прибыло на хранение",
        'storageLong' => "DATE в TIME... Авто car_title (VIN vin_number) прибыло на хранение. Принял user_role user_email.",

        'issuanceShort' => "Заявка на выдачу для авто car_title",
        'issuanceLong' => "DATE в TIME... Заявка на выдачу для авто car_title (VIN vin_number). Создал user_role user_email",

        'issuedShort' => 'Авто car_title снято с хранения',
        'issuedLong' => "DATE в TIME... Авто car_title (VIN vin_number) снято с хранения. Выдал user_role user_email.",

        'denied-for-storageShort' => 'Авто car_title отклонено на хранение',
        'denied-for-storageLong' => "DATE в TIME... Авто car_title (VIN vin_number) отклонено на хранение. Отклонил user_role user_email",

        'cancelled-by-partnerShort' => 'Авто car_title отклонено на хранение',
        'cancelled-by-partnerLong' => "DATE в TIME... Авто car_title (VIN vin_number) отклонено на хранение партнером. Отклонил user_role user_email",

        'cancelled-by-usShort' => 'Авто car_title отклонено на хранение',
        'cancelled-by-usLong' => "DATE в TIME... Авто car_title (VIN vin_number) отклонено на хранение нами. Отклонил user_role user_email",

        'cancelled-byShort' => 'Авто car_title отклонено на хранение нами',
        'cancelled-byLong' => "DATE в TIME... Авто car_title (VIN vin_number) отклонено на хранение нами. Отклонил user_role user_email",

        'deletedShort' => 'Авто car_title удалено',
        'deletedLong' => "DATE в TIME... Авто car_title (VIN vin_number) удалено. Удалено user_role user_email",


        'viewRequestShort1' => "Заявка на осмотр авто car_title",
        'viewRequestLong1' => "DATE в TIME... Заявка на осмотр для авто car_title (VIN vin_number). Создал user_role user_email",

        'viewRequestShort2' => 'Авто car_title осмотрено',
        'viewRequestLong2' => "DATE в TIME... Авто car_title (VIN vin_number) осмотрено. user_role user_email",

        'viewRequestShort3' => 'Авто car_title не осмотрено',
        'viewRequestLong3' => "DATE в TIME... Авто car_title (VIN vin_number) не осмотрено.",
    ];

    public function __construct($application = null, $itemView = null, $user = null)
    {
        if($user !=null){
            $this->user = $user;
        }else{
            $this->user = auth()->user();
        }

        $this->application = $application;
        $this->applicationView = $itemView;
        if ($application == null && $itemView != null) {
            $this->application = $itemView->application;
        }
        $this->status = Status::find($this->application->status_id);
        $this->getUsers()
            ->getApplicationMessage()
            ->getViewRequestMessage();
    }

    public function getViewRequestMessage()
    {
        if ($this->applicationView == null) {
            return $this;
        }
        $messages = $this->generateMessages();

        $this->applicationViewMessage = [
            'short' => $messages['viewRequestShort' . $this->applicationView->status_id],
            'long' => $messages['viewRequestLong' . $this->applicationView->status_id],
            'id' => $this->applicationView->application_id,
            'user_id' => $this->user->id
        ];
        return $this;
    }

    public function getApplicationMessage()
    {
        if ($this->applicationView != null) {
            return $this;
        }
        $messages = $this->generateMessages();

        $this->applicationMessage = [
            'short' => $messages[$this->status->code . 'Short'],
            'long' => $messages[$this->status->code . 'Long'],
            'id' => $this->application->id,
            'user_id' => $this->user->id
        ];
        return $this;
    }

    public function parseMessage($messageSearchReplace)
    {
        $arr = [];
        foreach ($this->messages as $key => $message) {
            $arr[$key] = str_replace(array_keys($messageSearchReplace), array_values($messageSearchReplace), $message);
        }
        return $arr;
    }

    public function generateMessages()
    {
        $messages = [
            'car_title' => $this->application->car_title,
            'vin_number' => $item->vin ?? $this->application->license_plate,
            'VIN' => $this->application->vin ? 'VIN' : 'Гос.номер ',
            'user_email' => $this->user->email,
            'user_role' => $this->user->getRoleNames()->first(),
            'DATE' => Carbon::now()->format('d.m.Y'),
            'TIME' => Carbon::now()->format('H:m'),
        ];
        $arr = $this->parseMessage($messages);
        $arr['updated_at'] = $this->application->updated_at;
        $arr['arrived_at'] = $this->application->arrived_at;
        return $arr;
    }

    public function getPermission()
    {
        $deniedStatuses = [4, 5, 6];
        if (in_array($this->status->id, $deniedStatuses)) {
            return 'notify_app_denied';
        }
        if ($this->application->issuance) {
            return 'notify_app_issuance';
        }
        return 'notify_app_' . $this->status->code;
    }

    public function getUsers()
    {

        $users = collect([]);
        if ($this->application->partner && $this->application->partner->user) {
            $users = $this->application->partner->user->children;
            $users->push($this->application->partner->user);
        }

        if ($this->application->createdUser->owner) {
            $users = $this->application->createdUser->owner->children;
            $users->push($this->application->createdUser->owner);
        } else {

            $users->push($this->application->createdUser);
            $users->push($this->application->createdUser->children);
        }

        $users = $users->push(User::find($this->superAdmin));
        $users = $users->unique('id')->all();

        $permission = $this->getPermission();
        $users = collect(array_filter($users))->reject(function ($user) use ($permission) {
            return !$user->hasPermissionTo($permission);
        });
        $this->users = $users;
        return $this;
    }
}
