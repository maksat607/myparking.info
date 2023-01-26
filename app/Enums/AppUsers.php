<?php

namespace App\Enums;

use App\Models\User;
use App\Notifications\TelegramNotification;
use Illuminate\Support\Facades\Http;
use function React\Promise\all;

class AppUsers
{
    public function __construct(
        public $application,
        public $exceptions = [],
    ) {
    }



    public function partnerUsers()
    {
        $users = collect([]);
        if ($this->application->partner && $this->application->partner->user) {
            $users = $users->merge($this->application->partner->user->children);
            $users->push($this->application->partner->user);
        }
        return $users->filter()->unique('id')->all();

    }

    public function storageUsers()
    {
        $users = collect([]);
        $users = $users->merge($this->application->parking->owner->children);
        $user_managers = $this->application->parking->managers;
        $user_managers = $user_managers->filter();

        $users->push($this->application->parking->owner);

        $users = collect(($users->filter()))->reject(function ($user) use ($user_managers) {
            return $user->getRole()=='Manager';
        })->merge($user_managers);
        $users = collect(($users->filter()))->reject(function ($user) use ($user_managers) {
            return in_array($user->getRole(),$this->exceptions);
        });
        return $users->filter()->unique('id')->all();

    }
    public function allUsers()
    {
        $users = $this->partnerUsers();
        $users = array_merge($users,$this->storageUsers());
        return $users;
    }
}
