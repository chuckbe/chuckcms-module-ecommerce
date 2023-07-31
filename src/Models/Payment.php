<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Models;

use Eloquent;
use ChuckEcommerce;

class Payment extends Eloquent
{
    const STATUS_CREATED = 'new';
    const STATUS_AWAITING = 'awaiting';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ERROR = 'error';
    const STATUS_PAYMENT = 'paid';
    const STATUS_REFUND = 'refund';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'mollie_id', 'type', 'amount', 'status'
    ];

    public function order()
    {
        return $this->belongsTo('Chuckbe\ChuckcmsModuleEcommerce\Models\Order');
    }
}
