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

    public $redirectTo;

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
     * Attach the user role class to the user
     * @return App\User Chainable
     */
    public function bootRoles() // Only happens when user is logged in
    {
        $rolePaths = [
            '\\App\\Roles\\',
            '\\Baytek\\Laravel\\Users\\Roles\\'
        ];

        // If the roles have not yet been processed
        if(empty($this->cachedRoles)) {

            foreach (Role::all() as $role) {

                if ($this->hasRole($role->name)) {

                    // Loop though the paths and search for the role
                    foreach ($rolePaths as $path) {

                        // Define the role class we are looking for
                        $roleClass = $path . $role->name;

                        // Check to see if it exists
                        if (class_exists($roleClass)) {

                            $roleInstance = new $roleClass($this);

                            $this->cachedRoles = collect($this->cachedRoles)
                                ->put(strtolower($role->name), $roleInstance)
                                ->all();

                            break;
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Encrypt the value of the password upon being set. It should not be possible to change an existing users password, they should trigger a password reset.
     * @param mixed $value The password value unencrypted
     */
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = encrypt($value);
    // }

    public function getMetaRecord($key)
    {
        $meta = $this->restrictedMeta->where('key', $key);
        if($meta->count()) {
            return $meta->first();
        }
        return null;
    }

    public function scopeWithMeta($query, $restricted = false)
    {
        return $query->with($restricted ? 'restrictedMeta' : 'meta');
    }

    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    public function restrictedMeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id')->withoutGlobalScope('not_restricted');
    }
}
