<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Users\Scopes\ApprovedMemberScope;
use Baytek\Laravel\Users\Scopes\MetadataScope;
use Baytek\Laravel\Users\User;

use Illuminate\Support\Facades\Request;

use Baytek\Laravel\StatusBit\Statusable;
use Baytek\Laravel\StatusBit\Interfaces\StatusInterface;

class Member extends User implements StatusInterface
{
    use Statusable;

    protected $table = 'users';

    public $meta = [
        'avatar',
        'first_name',
        'last_name',
        'phone',
        'title',
        'company',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new MetadataScope);

        if(route('login') == Request::url() && Request::instance()->request->get('email') == User::find(1)->email) {
            return;
        }

        if(\Auth::user() && \Auth::user()->hasRole('Root')) {
            return;
        }

        static::addGlobalScope(new ApprovedMemberScope);
    }

	public function getRouteKeyName()
	{
	    return 'id';
	}

    /**
     * A user may have multiple roles.
     * This is an override from the https://github.com/spatie/laravel-permission package.
     * It did not have setting for the key used, third value  for belongsToMany
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.role'),
            config('laravel-permission.table_names.user_has_roles'),
            'user_id'
        );
    }

    /**
     * A user may have multiple direct permissions.
     * This is an override from the https://github.com/spatie/laravel-permission package.
     * It did not have setting for the key used, third value  for belongsToMany
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            config('laravel-permission.models.permission'),
            config('laravel-permission.table_names.user_has_permissions'),
            'user_id'
        );
    }

    /**
     * Scope a query to only include pending members (require moderation).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->withStatus(['exclude' => [Member::APPROVED, Member::DELETED]]);
    }

    /**
     * Scope a query to only include deleted members.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->withStatus(Member::APPROVED);
    }

    /**
     * Scope a query to only include deleted members.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeclined($query)
    {
        return $query->withStatus(Member::DELETED);
    }

    /**
     * Orders the query by the specified meta key value.
     */
    public function scopeOrderByMeta($query, $key, $direction = 'asc')
    {
        return $query
            ->join('user_meta AS metadata_order', function($join) use ($key) {
                $join->on('users.id', '=', 'metadata_order.user_id')
                    ->where('metadata_order.key', $key);
            })
            ->orderBy('metadata_order.value', $direction);
    }

    public function scopeWhereMetadata($query, $key, $value, $comparison = '=')
    {
        return $query
            ->join('user_meta AS metadata', function($join) use ($key, $value, $comparison) {
                $join->on('users.id', '=', 'metadata.user_id')
                    ->where('metadata.key', $key)
                    ->where('metadata.value', $comparison, $value);
            });
    }
}
