<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderStatusWasUpdated;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid;
use Chuckbe\ChuckcmsModuleEcommerce\Payment\Payment;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;
use Illuminate\Http\Request;
use ChuckEcommerce;
use ChuckSite;
use Mollie;

class PaymentRepository
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Initiate a new payment for the order
     *
     * @var 
     **/
    public function initiate(Order $order)
    {
        if ( $order->json['payment_method'] == 'banktransfer' 
            && ChuckEcommerce::getSetting('integrations.banktransfer.active') ) {
            return new Payment($order);
        }

        config(['mollie.key' => ChuckSite::module('chuckcms-module-ecommerce')->getSetting('integrations.mollie.key')]);

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format( ( (float)$order->final ), 2, '.', ''), // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => config('chuckcms-module-ecommerce.order.payment_description') . $order->json['order_number'],
            'webhookUrl' => 'https://chuckcms.com/', //route('module.ecommerce.mollie_webhook'),
            'redirectUrl' => route('module.ecommerce.checkout.followup', ['order_number' => $order->json['order_number']]),
            'method' => $order->json['payment_method'],
            "metadata" => array(
                'amount' => number_format( ( (float)$order->final ), 2, '.', ''),
                'order_id' => $order->id,
                'order_number' => $order->json['order_number']
                )
        ]);

        $order_json = $order->json;
        $order_json['payments'][$payment->id] = ['id' => $payment->id, 'status' => 'awaiting']; //use orderrepo to add payment details to order
        $order->json = $order_json;
        $order->save();

        $payment = Mollie::api()->payments()->get($payment->id);

        return $payment;
    }

    public function check(Order $order)
    {
        if($order->isPaid()) {
            return;
        }

        if ( $order->status == Order::STATUS_AWAITING_TRANSFER ) {
            return;
        }

        if ( $order->json['payment_method'] == 'banktransfer' 
            && ChuckEcommerce::getSetting('integrations.banktransfer.active')
            && $order->status !== Order::STATUS_AWAITING_TRANSFER) {
            event(new OrderStatusWasUpdated($order, Order::STATUS_AWAITING_TRANSFER));
            return;
        }

        config(['mollie.key' => ChuckSite::module('chuckcms-module-ecommerce')->getSetting('integrations.mollie.key')]);

        $total = 0;
        $json = $order->json;
        foreach($order->json['payments'] as $payId => $payment) {
            $mollie = Mollie::api()->payments()->get($payId);
            if($mollie->isPaid() && $order->json['payments'][$payId]['status'] == 'awaiting') {
                $json['payments'][$payId]['status'] = Order::STATUS_PAYMENT;
                $total = $total + (float)$mollie->amount->value;
            }
        }

        $order->json = $json;
        $order->update();

                
        if($total >= $order->final) {
            event(new OrderWasPaid($order));
        }
    }

}