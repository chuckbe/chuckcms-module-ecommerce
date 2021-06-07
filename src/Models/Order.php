<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Eloquent;
use ChuckEcommerce;

class Order extends Eloquent
{
    const STATUS_CREATED = 'new';
    const STATUS_AWAITING = 'awaiting';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ERROR = 'error';
    const STATUS_PAYMENT = 'payment';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_DELIVERY = 'delivery';
    const STATUS_REFUND = 'refund';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'status', 'surname', 'name', 'email', 'tel', 'subtotal', 'tax', 'shipping', 'total', 'json'
    ];

    protected $casts = [
        'json' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo('Chuckbe\ChuckcmsModuleEcommerce\Models\Customer');
    }

    public function getHasDiscountAttribute()
    {
        return $this->hasDiscount();
    }

    public function getIsTaxedAttribute()
    {
        return $this->isTaxed();
    }

    public function hasDiscount()
    {
        return array_key_exists('discounts', $this->json) ? (count($this->json['discounts']) > 0) : false;
    }

    public function isTaxed()
    {
        return array_key_exists('is_taxed', $this->json) ? (bool)$this->json['is_taxed'] : true;
    }

    public function isPaid()
    {
        return ChuckEcommerce::getSetting('order.statuses.'.$this->status.'.paid');
    }

    public function hasInvoice()
    {
        return array_key_exists('invoice_number', $this->json);
    }

    public function taxRates()
    {
        $rates = [];
        foreach ($this->json['products'] as $sku => $item) {
            if( isset($item['extras']) && count($item['extras']) > 0 ) {
                foreach($item['extras'] as $eKey => $eValue) {
                    $rates[] = (int)$eValue['vat']['amount'];    
                }
            }

            $rates[] = $item['tax_rate'];
        }
        return array_unique($rates);
    }

    public function taxForRate($rate)
    { 
        $taxes = [];
        foreach ($this->json['products'] as $sku => $item) {
            if( array_key_exists('_price', $item) ) {
                foreach($item['_price']['taxes'] as $allTaxes) {
                    if($allTaxes['rate'] == $rate) {
                        $taxes[] = $allTaxes['value'];
                    }
                }
            } else {
                if($rate == $item['tax_rate']) {
                    $taxes[] = $item['tax_total'];
                }
            }
        }
        return array_sum($taxes);
    }

    public function getInvoiceFileNameAttribute()
    {
        return 'factuur_' . ChuckEcommerce::getSetting('invoice.prefix') . str_pad($this->json['invoice_number'], 4, '0', STR_PAD_LEFT) . '.pdf';
    }

    public function getDeliveryFileNameAttribute()
    {
        return 'levering_' . $this->json['order_number'] . '.pdf';
    }

    /**
     * Return the discount
     *
     * @param  $price
     * @return int|float
     */
    protected function calculateDiscounts($price, $discounts)
    {
        $totalDiscount = 0;
        foreach($discounts as $discountCode => $discount) { //sort by priority
            //if conditions match
            $totalDiscount = $totalDiscount + $this->calculateDiscount($price, $discount);
            $price = $this->applyDiscount($price, $discount);
        }

        return $totalDiscount;
    }

    /**
     * Return the discountedPrice
     *
     * @param  $price
     * @return int|float
     */
    public function calculateDiscountedPrice($price, $discounts)
    {
        $totalDiscount = 0;
        foreach($discounts as $discountCode => $discount) { //sort by priority
            //if conditions match
            $totalDiscount = $totalDiscount + $this->calculateDiscount($price, $discount);
            $price = $this->applyDiscount($price, $discount);
        }

        return $price;
    }

    protected function applyDiscount($price, $discount)
    {
        return $price - $this->calculateDiscount($price, $discount);
    }

    protected function calculateDiscount($price, $discount)
    {
        switch ($discount['type']) {
            case 'currency':
                return ($discount['value'] > $price) ? 0 : $discount['value'];
                break;
            case 'percentage':
                return ($price * ($discount['value'] / 100));
                break;
        }
    }
}
