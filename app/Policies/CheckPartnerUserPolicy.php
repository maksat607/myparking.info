<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CheckPartnerUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Partner $partner
     * @return mixed
     */
    public function issetPartnerUser(User $user, Partner $partner)
    {
        return (is_null($partner->user))
            ? Response::allow()
            : Response::deny(__('The partner already has a linked user!'));

    }

}
