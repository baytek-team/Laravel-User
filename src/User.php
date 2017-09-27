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
        'email',
        'status'
    ];

    /**
     * Variable used to store the metadata fields while the user is saving,
     * @var array
     */
    protected $metadataAttributes = [];

    /**
     * Location where the user is redirect to after they log in.
     * @var string
     */
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
     * The constructor method of the model.
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        // Fill the fillable array with metadata
        if(property_exists($this, 'metadata')) {
            $this->fillable(array_merge($this->fillable, $this->metadata));
        }

        parent::__construct($attributes);
    }

    /**
     * Boot method of the model
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if(property_exists($model, 'metadata')) {
                $model->metadataAttributes = collect($model->attributes)->only($model->metadata)->all();
                $model->attributes = collect($model->attributes)->except($model->metadata)->all();
            }
        });

        // After the model has been saved.
        self::created(function ($model) {
            // Check if there is any metadata to save.
            if(property_exists($model, 'metadata')) {
                $model->saveMetadata($model->metadataAttributes);
            }
        });

        self::updating(function ($model) {
            if(property_exists($model, 'metadata')) {
                $model->metadataAttributes = collect($model->attributes)->only($model->metadata)->all();
                $model->attributes = collect($model->attributes)->except($model->metadata)->all();
            }
        });

        self::updated(function ($model) {
            // Check if there is any metadata to save.
            if(property_exists($model, 'metadata')) {
                $model->saveMetadata($model->metadataAttributes);
            }
        });
    }

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


    /**
     * Save the metadata for this model
     * @param  mixed $key   Either
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function saveMetadata($key, $value = null)
    {
        if(is_string($key)) {
            $set = collect([$key => $value]);
        }
        else if(is_array($key)) {
            $set = collect($key);
        }
        else if(is_object($key) && $key instanceof Collection) {
            $set = $key;
        }

        $set->each(function ($value, $key) {

            // Check to see if the metadata already exists
            $metadata = UserMeta::where([
                'user_id' => $this->id,
                'language' => \App::getLocale(),
                'key' => $key
            ])->get();

            if($metadata->count()) {
                $metadata->first()->value = $value;
                $metadata->first()->save();
            }
            else {
                $meta = (new UserMeta([
                    'user_id' => $this->id,
                    'key' => $key,
                    'language' => \App::getLocale(),
                    'value' => $value,
                ]));

                $meta->save();
                $this->meta()->save($meta);
            }
        });
    }
}
