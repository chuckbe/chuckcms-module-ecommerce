<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Eloquent;

use Spatie\Translatable\HasTranslations;

class Product extends Eloquent
{
    use HasTranslations;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    public $translatable = ['slug', 'json'];
}
