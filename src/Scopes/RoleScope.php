<?php

namespace Baytek\Laravel\Users\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RoleScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(count($model->getRoles())) {
            $builder->whereHas('roles', function ($query) use ($model) {
                $query->whereIn('roles.name', collect($model->getRoles())->map(function($role){
                    return $role::ROLE;
                }));
            });
        }
    }
}