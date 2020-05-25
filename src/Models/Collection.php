<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Chuckbe\Chuckcms\Models\Scopes\RepeatableScope;
use Chuckbe\Chuckcms\Models\Repeater;

class Collection extends Repeater
{
    protected $table = 'repeaters';
    
	/**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new RepeatableScope(config('chuckcms-module-ecommerce.collections.slug')));

        parent::boot();
    }
}
