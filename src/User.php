<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Content\Models\Concerns\HasMetadata;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasPermissions, HasMetadata;

    /**
     * Table name used to select users
     * @var string
     */
    protected $table = 'users';

    /**
     * List of roles to be assigned when the user model is saved.
     * @var array
     */
    protected $assignRoles = [];

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
     * List of fields which should be cast when rendering JSON
     * @var array
     */
    protected $casts = [
        'status' => 'int'
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

            foreach($model->getRoles() as $role) {
                $model->assignRole($role::ROLE);
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

        static::addGlobalScope(new Scopes\RoleScope);
    }


    /**
     * Get the list of roles
     * @return array List of roles
     */
    public function getRoles()
    {
        return $this->assignRoles;
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
     * Get a user metadata record
     * @param  string $key The key of the metadata we'd like to get
     * @return mixed       The value of metadata record
     */
    public function getMetaRecord($key)
    {
        $meta = $this->restrictedMeta->where('key', $key);
        if($meta->count()) {
            return $meta->first();
        }
        return null;
    }

    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMeta($query, $restricted = false)
    {
        return $query->with($restricted ? 'restrictedMeta' : 'meta');
    }

    /**
     * A user may have metadata.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    /**
     * A user may have restricted metadata.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restrictedMeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id')->withoutGlobalScope('not_restricted');
    }

    /**
     * Save the metadata for this model
     * @param  mixed $key    Either array or metadata key
     * @param  mixed $value  Optional value for the metadata
     * @return void
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
