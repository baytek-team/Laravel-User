<?php

namespace Baytek\Laravel\Users\Policies;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Roles\Administrator;
use Baytek\Laravel\Users\Roles\Root;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->hasAnyRole(['Root', 'Administrator']);
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Users\User  $member
     * @return mixed
     */
    public function view(User $user, User $member)
    {
        return $user->id === $member->id;
    }

    /**
     * Determine whether the user can create contents.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Users\User  $member
     * @return mixed
     */
    public function update(User $user, User $member)
    {
        return $user->id === $member->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Users\User  $member
     * @return mixed
     */
    public function delete(User $user, User $member)
    {
        return $user->id === $member->id;
    }
}
