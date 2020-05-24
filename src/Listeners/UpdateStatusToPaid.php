<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Listeners;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;

class UpdateStatusToPaid
{
	private $orderRepository;

	public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handle(OrderWasPaid $event)
    {
    	$order = $this->orderRepository->updateStatus($event->order, Order::STATUS_PAYMENT);
    }
}