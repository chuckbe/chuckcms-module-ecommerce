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

    public function getCompanyAttribute() 
    {
        return array_key_exists('company', $this->json) ? $this->json['company'] : array();
    }

    public function getGuestAttribute() :bool
    {
        return $this->user_id == null;
    }

    public function getBillingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? 
            $this->json['address']['billing'] : 
            array();
    }

    public function getShippingAddressAttribute() 
    {
        return array_key_exists('address', $this->json) ? 
            ($this->json['address']['shipping_equal_to_billing'] ? 
                $this->json['address']['billing'] : 
                $this->json['address']['shipping']) : 
            array();
    }

    public function getMollieBillingAddressAttribute() 
    {
        $address = $this->billing_address;
        $company = $this->company;

        return $this->formatMollieAddress($address, $company);
    }

    public function getMollieShippingAddressAttribute() 
    {
        $address = $this->shipping_address;
        $company = $this->company;

        return $this->formatMollieAddress($address, $company);
    }

    protected function formatMollieAddress(array $address, array $company)
    {
        $mollie = [];

        if ( empty($address) ) {
            return $mollie;
        }

        if ( !empty($company) ) {
            $mollie['organizationName'] = $company['name'];
        }

        $mollie['streetAndNumber'] = $address['street'] . ' ' . $address['housenumber'];
        $mollie['city'] = $address['city'];
        //$mollie['region'] = "optional",
        $mollie['postalCode'] = $address['postalcode'];
        $mollie['country'] = $address['country'];
        //$mollie['title'] = "optional",
        $mollie['givenName'] = $this->surname;
        $mollie['familyName'] = $this->name;
        $mollie['email'] = $this->email;

        return $mollie;
    }

    protected $casts = [
        'json' => 'array',
    ];
}
