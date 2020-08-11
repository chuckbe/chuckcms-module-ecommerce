<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Chuckbe\Chuckcms\Models\Scopes\RepeatableScope;
use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Brand;

class Product extends Repeater
{
    protected $table = 'repeaters';
    
	/**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new RepeatableScope(config('chuckcms-module-ecommerce.products.slug')));

        parent::boot();
    }

    public function getBrandAttribute()
    {
        return Brand::where('id', (int)$this->json['brand'])->first();
    }
}
