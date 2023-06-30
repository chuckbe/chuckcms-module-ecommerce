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

        if ( config('chuckcms-module-ecommerce.integrations.mollie.methods.'.$order->json['payment_method'].'.api') == 'orders' ) {
            $payment = $this->initiateOrder($order);

            $order_json = $order->json;
            $order_json['payments'][$payment->id] = ['id' => $payment->id, 'status' => 'created', 'type' => 'mollie-orders']; //use orderrepo to add payment details to order
            $order->json = $order_json;
            $order->save();
        } else {
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
            $order_json['payments'][$payment->id] = ['id' => $payment->id, 'status' => 'awaiting', 'type' => 'mollie-payments']; //use orderrepo to add payment details to order
            $order->json = $order_json;
            $order->save();
        }
        

        

        //$payment = Mollie::api()->payments()->get($payment->id);

        return $payment;
    }

    /**
     * Initiate a new Mollie Order for the order
     *
     * @var 
     **/
    public function initiateOrder(Order $order)
    {
        $mollie = Mollie::api()->orders()->create([
            "amount" => [
                "value" => number_format( ( (float)$order->final ), 2, '.', ''),
                "currency" => "EUR"
            ],
            "billingAddress" => $order->customer->mollie_billing_address,
            "shippingAddress" => $order->customer->mollie_shipping_address,
            "metadata" => [
                "amount" => number_format( ( (float)$order->final ), 2, '.', ''),
                "order_id" => $order->id,
                "order_number" => $order->json['order_number']
            ],
            "locale" => "nl_BE", //@TODO
            "orderNumber" => $order->json['order_number'],
            "redirectUrl" => route('module.ecommerce.checkout.followup', ['order_number' => $order->json['order_number']]),
            "webhookUrl" => 'https://chuckcms.com/', //route('module.ecommerce.mollie_webhook'),
            "method" => config('chuckcms-module-ecommerce::integrations.mollie.methods.'.$order->json['payment_method'].'.method'),
            "lines" => $this->orderRepository->mollieLines($order)
        ]);

        return $mollie;
    }

    public function check(Order $order)
    {
        if ($order->isPaid()) {
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
            if ( substr($payId, 0, 3) === "ord") {
                $mollie = Mollie::api()->orders()->get($payId, ['embed' => 'payments']);
            } else {
                $mollie = Mollie::api()->payments()->get($payId);
            }
            
            if($mollie->isPaid() && $order->json['payments'][$payId]['status'] == 'awaiting') {
                $json['payments'][$payId]['status'] = Order::STATUS_PAYMENT;
                $total = $total + (float)$mollie->amountCaptured->value;
            }
        }

        $order->json = $json;
        $order->update();

                
        if($total >= $order->final) {
            event(new OrderWasPaid($order));
        }
    }

}