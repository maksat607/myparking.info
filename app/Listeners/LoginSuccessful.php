<?php

namespace App\Listeners;

use App\Models\Role;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Session;
class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  IlluminateAuthEventsLogin  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $sessionArr = [];
        $roles = collect(['SuperAdmin','Admin','Manager','Operator','Partner','PartnerOperator','Moderator']);
        foreach ($roles as $role){
            if($event->user->hasRole($role))
                $sessionArr[$role."Hide"] = 'd-none';
            else
                $sessionArr[$role."Hide"] = '';
        }

        session($sessionArr);



    }
}
