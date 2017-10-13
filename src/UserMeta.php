<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\StatusBit\Statusable;
use Baytek\Laravel\StatusBit\Interfaces\StatusInterface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class UserMeta extends Model implements StatusInterface
{
    use Statusable;
    /**
     * Set the user meta table
     * @var string
     */
    protected $table = 'user_meta';

    /**
     * Set the fillable fields
     * @var array
     */
    protected $fillable = [
        'language',
        'status',
        'key',
        'value',
    ];

    /**
     * Do not use timestamps
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Model boot method
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('not_restricted', function (Builder $builder) {
            $builder->withStatus(['exclude' => [self::RESTRICTED]]);
        });
    }

    /**
     * User model relation
     * @return BelongsTo User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
