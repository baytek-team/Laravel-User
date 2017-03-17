<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Content\Models\Concerns\HasMetadata;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasMetadata;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Encrypt the value of the password upon being set. It should not be possible to change an existing users password, they should trigger a password reset.
     * @param mixed $value The password value unencrypted
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    public function getMetaRecord($key)
    {
        $meta = $this->meta->where('key', $key);
        if($meta->count()) {
            return $meta->first();
        }
        return null;
    }

    public function getMeta($key, $default = null)
    {
        if($meta = $this->getMetaRecord($key)) {
            return $meta->value;
        }

        return $default;
    }

    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }
}
