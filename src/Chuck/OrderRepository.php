<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Requests\PlaceOrderRequest;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CartRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Http\Request;
use ChuckEcommerce;
use Carbon\Carbon;
use ChuckSite;
use Mail;
use PDF;

class OrderRepository
{
    private $customerRepository;
    private $cartRepository;

	public function __construct(
        CustomerRepository $customerRepository, 
        CartRepository $cartRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->cartRepository = $cartRepository;
    }

    public function followup($order_number)
    {
        $order = Order::where('json->order_number', $order_number)->first();
        return view('chuckcms-module-ecommerce::followup.detail', compact('order'))->render();
    }

    public function followupScripts($order_number)
    {
        $order = Order::where('json->order_number', $order_number)->first();
        return view('chuckcms-module-ecommerce::followup.scripts', compact('order'))->render();
    }

    public function getForCustomer(Customer $customer)
    {
        return $customer->orders;
    }

    public function getByOrderNumber($order_number)
    {
        return Order::where('json->order_number', $order_number)->first();
    }

    /**
     * Place a new order
     *
     * @var 
     **/
    public function new(PlaceOrderRequest $request, $cart, $products, $customer)
    {
        $order = new Order();
        $order->customer_id = $customer->id;
        $order->status = Order::STATUS_CREATED;
        $order->surname = $customer->surname;
        $order->name = $customer->name;
        $order->email = $customer->email;
        $order->tel = $customer->tel;

        $json = [];

        $json['order_number'] = str_random(12); // check if already exists (not so important cus this is not the main identifier)
        $json['payment_method'] = $request->get('payment_method');
        $json['products'] = $this->cartRepository->formatProducts($products, $cart);
        $json['shipping'] = ChuckEcommerce::getCarrier($request->get('shipping_method'));
        $json = $this->customerRepository->mapAddress($request, $json); 
        $json = $this->customerRepository->mapCompany($request, $json); 

        $order->subtotal = $cart->total() - $cart->tax();
        $order->subtotal_tax = $cart->tax();
        $order->shipping = ChuckEcommerce::getCarrierSubtotal($request->get('shipping_method'), 21);
        $order->shipping_tax = ChuckEcommerce::getCarrierTax($request->get('shipping_method'), 21);
        $order->total = $order->subtotal + $order->shipping;
        $order->total_tax = $order->subtotal_tax + $order->shipping_tax;
        $order->final = $order->total + $order->total_tax;

        $order->json = $json;

        if($order->save()) {
            return $order;
        } else {
            return null;
        }
    }

    public function updateStatus(Order $order, $status)
    {
        $status_object = ChuckEcommerce::getSetting('order.statuses.'.$status);
        $order->status = $status;
        $json = $order->json;
        if($status_object['invoice'] && !array_key_exists('invoice_number', $json)) {
            $json['invoice_number'] = $this->generateInvoiceNumber();
            $order->json = $json;
        }

        $order->update();

        if($status_object['send_email']) {
            if($status_object['invoice']) {
                $pdf = $this->generatePDF($order);
            } else {
                $pdf = null;
            }

            $this->sendConfirmation($order, $status_object['invoice'], $pdf);
            $this->sendNotification($order, $status_object['invoice'], $pdf);
        }
    }

    private function sendConfirmation(Order $order, $invoice = false, $pdf = null)
    {
        Mail::send('chuckcms-module-ecommerce::emails.confirmation', ['order' => $order], function ($m) use ($order, $invoice, $pdf) {
            $m->from(config('chuckcms-module-ecommerce.emails.from_email'), config('chuckcms-module-ecommerce.emails.from_name'));
            $m->to($order->customer->email, $order->customer->surname.' '.$order->customer->name)->subject(config('chuckcms-module-ecommerce.emails.confirmation_subject').$order->json['order_number']);
            if ($invoice) {
                $m->attachData($pdf, 'factuur_' . ChuckEcommerce::getSetting('invoice.prefix') . str_pad($order->json['invoice_number'], 4, '0', STR_PAD_LEFT) . '.pdf', ['mime' => 'application/pdf']);
            }
        });    
    }

    private function sendNotification(Order $order, $invoice = false, $pdf = null)
    {
        Mail::send('chuckcms-module-ecommerce::emails.notification', ['order' => $order], function ($m) use ($order, $invoice, $pdf) {
            $m->from(config('chuckcms-module-ecommerce.emails.from_email'), config('chuckcms-module-ecommerce.emails.from_name'));
            $m->to(config('chuckcms-module-ecommerce.emails.to_email'), config('chuckcms-module-ecommerce.emails.to_name'))->subject(config('chuckcms-module-order-form.emails.notification_subject').$order->json['order_number']);
            if ($invoice) {
                $m->attachData($pdf, $order->invoiceFileName, ['mime' => 'application/pdf']);
            }
            

            if( config('chuckcms-module-ecommerce.emails.to_cc') !== false){
                $m->cc(config('chuckcms-module-ecommerce.emails.to_cc'));
            }

            if( config('chuckcms-module-ecommerce.emails.to_bcc') !== false){
                $m->bcc(config('chuckcms-module-ecommerce.emails.to_bcc'));
            }
        });    
    }

    private function generatePDF(Order $order)
    {
        $pdf = PDF::loadView('chuckcms-module-ecommerce::pdf.invoice', compact('order'));
        return $pdf->output();
    }

    public function downloadInvoice(Order $order)
    {
        $pdf = PDF::loadView('chuckcms-module-ecommerce::pdf.invoice', compact('order'));
        return $pdf->download($order->invoiceFileName);
    }

    private function generateInvoiceNumber()
    {
        $invoice_number = ChuckEcommerce::getSetting('invoice.number') + 1;
        ChuckEcommerce::setSetting('invoice.number', $invoice_number);
        return $invoice_number;
    }

    public function totalSales()
    {
        $total = Order::where('status', 'payment')->sum('final');
        
        return ChuckEcommerce::formatPrice($total);
    }

    public function totalSalesLast7Days()
    {
        $total = Order::where('status', 'payment')->whereDate('created_at', '>', Carbon::today()->subDays(7))->sum('final');

        return ChuckEcommerce::formatPrice($total);
    }

    public function totalSalesLast7DaysQty()
    {
        $total = Order::where('status', 'payment')->whereDate('created_at', '>', Carbon::today()->subDays(7))->count();

        return $total;
    }

}