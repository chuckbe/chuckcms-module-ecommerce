<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Listeners;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderStatusWasUpdated;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;

class UpdateOrderStatus
{
	private $orderRepository;

	public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(OrderStatusWasUpdated $event)
    {
    	$order = $this->orderRepository->updateStatus($event->order, $event->status);
    }
}