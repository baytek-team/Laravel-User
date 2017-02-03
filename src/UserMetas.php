<?php

namespace Baytek\Laravel\Users;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'user_metas';
    protected $fillable = [
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
