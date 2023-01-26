<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function viewUser(User $user, User $model)
    {
        if(auth()->user()->hasRole('SuperAdmin')) {
            return true;
        } elseif (auth()->user()->hasRole(['Manager', 'Operator'])) {
            return $model->parent_id == $user->owner->id;
        } else {
            return $model->parent_id == $user->id;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function updateUser(User $user, User $model)
    {
        if(auth()->user()->hasRole('SuperAdmin')) {
            return true;
        } elseif (auth()->user()->hasRole(['Manager', 'Operator'])) {
            return $model->parent_id == $user->owner->id;
        } else {
            return $model->parent_id == $user->id;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function deleteUser(User $user, User $model)
    {
        if(auth()->user()->hasRole('SuperAdmin')) {
            return true;
        } elseif (auth()->user()->hasRole(['Manager', 'Operator'])) {
            return $model->parent_id == $user->owner->id;
        } else {
            return $model->parent_id == $user->id;
        }
    }

    public function issetPartnerOperator(User $user)
    {
        if($user->hasRole(['Partner', 'PartnerOperator'])) {
//            return ($user->hasRole('Partner') && $user->children()->doesntExist())
//                ? Response::allow()
//                : Response::deny(__('The user already has an Operator!'));
            return Response::allow();
        }

        return true;
    }

}
