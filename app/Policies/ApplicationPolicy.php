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

        if (
            ($user->can('application_update')
            || ($user->can('application_to_accept_update')
            && $application->status->code == 'pending') )
            && ($application
                || $application->status->code == 'draft'
                || $application->status->code == 'cancelled-by-us'
                || $application->status->code == 'pending'


            )
            && $user->hasRole(['Operator', 'Partner', 'PartnerOperator'])
        ) {
            return true;
        } elseif (
            $user->can('application_update') &&
            $application->status->code != 'cancelled-by-us' &&
            $user->hasRole(['SuperAdmin', 'Admin', 'Manager','Moderator'])
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
        if (
            $user->can('application_delete') &&
            ($application->acceptions ||
                $application->status->code == 'draft' ||
                $application->status->code == 'cancelled-by-us'
            ) &&
            $user->hasRole(['Operator', 'Partner', 'PartnerOperator'])
        ) {
            return true;
        } elseif (
            $user->can('application_delete') &&
            $application->status->code != 'cancelled-by-us' &&
            $user->hasRole(['SuperAdmin', 'Admin', 'Manager','Moderator'])
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
    public function colorRed(User $user, Application $application)
    {
        if (
            $user->can('application_delete') &&
            ($application->acceptions ||
                $application->status->code == 'draft' ||
                $application->status->code == 'cancelled-by-us'
            ) &&
            $user->hasRole(['Operator', 'Partner', 'PartnerOperator'])
        ) {
            return true;
        } elseif (
            $user->can('application_delete') &&
            $application->status->code != 'cancelled-by-us' &&
            $user->hasRole(['SuperAdmin', 'Admin', 'Manager','Moderator'])
        ) {
            return true;
        }
    }
    protected function failedAuthorization()
    {
        $errorMessage = 'You are not authorized to update this post.';
        return response()->json(['error' => $errorMessage], 401);
    }
}
