<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can('application_view')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function view(User $user, Application $application)
    {

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('application_create')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function update(User $user, Application $application)
    {

        if(
            $user->can('application_update') &&
            $application->acceptions &&
            $user->hasRole(['Operator', 'Partner', 'PartnerOperator'])
        ) {
            return true;
        } elseif (
            $user->can('application_update') &&
            $user->hasRole(['SuperAdmin', 'Admin', 'Manager'])
        ) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Application  $application
     * @return mixed
     */
    public function delete(User $user, Application $application)
    {
        if(
            $user->can('application_delete') &&
            $application->acceptions &&
            $user->hasRole(['Operator', 'Partner', 'PartnerOperator'])
        ) {
            return true;
        } elseif (
            $user->can('application_delete') &&
            is_null($application->acceptions) &&
            $user->hasRole(['SuperAdmin', 'Admin', 'Manager'])
        ) {
            return true;
        }
    }

}
