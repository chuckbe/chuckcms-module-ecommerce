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
        'user_id', 'surname', 'name', 'email', 'tel', 'json'
    ];

    public function user()
    {
        return $this->belongsTo('Chuckbe\ChuckcmsModuleEcommerce\Models\User');
    }

    public function orders()
    {
        return $this->hasMany('Chuckbe\ChuckcmsModuleEcommerce\Models\Order');
    }

    public function getGuestAttribute() :bool
    {
        return $this->user_id == null;
    }

    public function getBillingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? $this->json['address']['billing'] : array();
    }

    public function getShippingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? $this->json['address']['shipping'] : array();
    }

    protected $casts = [
        'json' => 'array',
    ];
}
