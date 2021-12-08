<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Payment;

use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;

class Payment
{
    /**
     * Order object.
     *
     * @var \Chuckbe\ChuckcmsModuleEcommerce\Models\Order $order;
     */
    protected $order;

    /**
     * Amount of the payment.
     *
     * @var string $amount;
     */
    protected $amount;

    /**
     * Number of the order.
     *
     * @var string $order_number;
     */
    protected $order_number;

    /**
     * Payment constructor.
     *
     * @param \Chuckbe\ChuckcmsModuleEcommerce\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->amount = number_format( ( (float)$order->final ), 2, '.', '');
        $this->order_number = $order->json['order_number'];
    }

    public function getCheckoutUrl()
    {
        return route('module.ecommerce.checkout.followup', ['order_number' => $this->order_number]);
    }
}