<?php
 
namespace Chuckbe\ChuckcmsModuleEcommerce\Events;

use Illuminate\Queue\SerializesModels;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;

class OrderWasPaid
{
    use SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}