<?php

namespace Baytek\Laravel\Users;

use Baytek\Laravel\Content\Models\Content;

class File extends Content
{
    const EXCLUDED = 2 ** 9;  // Exclude from search

	/**
	 * Content keys that will be saved to the relation tables
	 * @var Array
	 */
	public $relationships = [
		'content-type' => 'file'
	];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

}
