<?php

namespace Baytek\Laravel\Users\Policies;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Roles\Administrator;
use Baytek\Laravel\Users\Roles\Root;

use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user is admin or root.
     * If so they can do all the things.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @return mixed
     */
    public function before(User $user)
    {
        return $user->can('Manage Roles');
    }

}
