<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Eloquent;

class Customer extends Eloquent
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'surname', 'name', 'tel', 'json'
    ];

    public function user()
    {
        return $this->belongsTo('Chuckbe\ChuckcmsModuleEcommerce\Models\User');
    }

    protected $casts = [
        'json' => 'array',
    ];
}
