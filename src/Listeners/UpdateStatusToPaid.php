<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Listeners;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;

class UpdateStatusToPaid
{
	private $orderRepository;

	public function __construct(DiscountRepository $discountRepository, OrderRepository $orderRepository)
    {
        $this->discountRepository = $discountRepository;
        $this->orderRepository = $orderRepository;
    }

    public function handle(OrderWasPaid $event)
    {
    	$order = $this->orderRepository->updateStatus($event->order, Order::STATUS_PAYMENT);
    	$this->discountRepository->addUsageByCustomer($event->order->json['discounts'], $event->order->customer);
    }
}