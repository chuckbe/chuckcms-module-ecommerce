<?php
 
namespace Chuckbe\ChuckcmsModuleEcommerce\Events;

use Illuminate\Queue\SerializesModels;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;

class OrderStatusWasUpdated
{
    use SerializesModels;

    public $order;
    public $status;

    public function __construct(Order $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
    }
}