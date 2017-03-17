<?php

namespace Baytek\Laravel\Users;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'user_meta';
    protected $fillable = [
        'language',
        'status',
        'key',
        'value',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
