<?php

namespace Baytek\Laravel\Users\Policies;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Policies\UserPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class MemberPolicy extends UserPolicy
{
    /**
     * Determine whether the user can view the user.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Users\User  $member
     * @return mixed
     */
    public function view(User $user, User $member = null)
    {
        return $user->can('View Member') || (!is_null($member) && $user->id === $member->id);
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
        return $user->can('Create Member');
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
        return $user->can('Update Member') || $user->id === $member->id;
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
        return $user->can('Delete Member') || $user->id === $member->id;
    }
}
