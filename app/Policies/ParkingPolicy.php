<?php

namespace App\Policies;

use App\Models\Parking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Arr;

class ParkingPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parking  $parking
     * @return mixed
     */
    public function viewParking(User $user, Parking $parking)
    {
        if($user->hasRole('SuperAdmin')) {
            return true;
        } elseif ($user->hasRole(['Moderator', 'Operator'])) {
            return $parking->user_id == $user->id;
        } else {
            return (($parking->user_id == $user->id) || (!is_null($user->children->find($parking->user_id))));
        }
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parking  $parking
     * @return mixed
     */
    public function updateParking(User $user, Parking $parking)
    {
        if($user->hasRole('SuperAdmin')) {
            return true;
        } elseif ($user->hasRole(['Moderator', 'Operator'])) {
            return $parking->user_id == $user->id;
        } else {
            return (($parking->user_id == $user->id) || (!is_null($user->children->find($parking->user_id))));
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parking  $parking
     * @return mixed
     */
    public function deleteParking(User $user, Parking $parking)
    {
        if($user->hasRole('SuperAdmin')) {
            return true;
        } elseif ($user->hasRole(['Moderator', 'Operator'])) {
            return $parking->user_id == $user->id;
        } else {
            return (($parking->user_id == $user->id) || (!is_null($user->children->find($parking->user_id))));
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parking  $parking
     * @return mixed
     */
    public function restore(User $user, Parking $parking)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Parking  $parking
     * @return mixed
     */
    public function forceDelete(User $user, Parking $parking)
    {
        return true;
    }
}
