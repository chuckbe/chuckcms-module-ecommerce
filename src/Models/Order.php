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
            $rates[] = $item['tax_rate'];
        }
        return array_unique($rates);
    }

    public function taxForRate($rate)
    {
        $taxes = [];
        foreach ($this->json['products'] as $sku => $item) {
            $taxes[] = $item['tax_total'];
        }
        return array_sum($taxes);
    }

    public function getInvoiceFileNameAttribute()
    {
        return 'factuur_' . ChuckEcommerce::getSetting('invoice.prefix') . str_pad($this->json['invoice_number'], 4, '0', STR_PAD_LEFT) . '.pdf';
    }
}
