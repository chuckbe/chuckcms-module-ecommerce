<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Requests\PlaceOrderRequest;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CartRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;
use ChuckCart;
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
        $json['shipping_tax_rate'] = ChuckEcommerce::getCarrierTaxRateForCart($request->get('shipping_method'), 'shopping');
        $json['discounts'] = $cart->discounts();
        $json['is_taxed'] = $cart->isTaxed;
        $json = $this->customerRepository->mapAddress($request, $json); 
        $json = $this->customerRepository->mapCompany($request, $json); 

        $order->subtotal = $cart->total();
        $order->subtotal_tax = $cart->tax();
        $order->discount = $cart->discount();
        $order->shipping = ChuckEcommerce::getCarrierSubtotalForCart($request->get('shipping_method'), 'shopping'); // get shipping vat according to shop address and customer address
        $order->shipping_tax = ChuckEcommerce::getCarrierTaxForCart($request->get('shipping_method'), 'shopping');
        
        $order->total = $cart->final() + $order->shipping + ($cart->isTaxed ? $order->shipping_tax : 0);
        $order->total_tax = ($order->subtotal_tax) + $order->shipping_tax;
        
        $order->final = $order->total + ($cart->isTaxed ? 0 : $order->total_tax);

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

            if(array_key_exists('template', $status_object['email']) && !is_array($status_object['email']['template'])) {
                $this->sendConfirmation($order, $status_object['invoice'], $pdf);
                $this->sendNotification($order, $status_object['invoice'], $pdf);
            } else {
                foreach($status_object['email'] as $emailKey => $email) {
                    $this->sendMail($order, $status_object, $emailKey, $email, $pdf);
                    sleep(1);
                }
            }
        }
    }

    private function sendMail(Order $order, array $status, string $emailKey, array $email, $pdf = null)
    {
        $invoice = $status['invoice'];
        $template = $email['template'];
        $to = $this->replaceEmailVariables($order, is_null($email['to']) ? '' : $email['to']);
        $to_name = $this->replaceEmailVariables($order, is_null($email['to_name']) ? '' : $email['to_name']);
        $cc = $this->replaceEmailVariables($order, is_null($email['cc']) ? '' : $email['cc']);
        $bcc = $this->replaceEmailVariables($order, is_null($email['bcc']) ? '' : $email['bcc']);

        if(array_key_exists('send_delivery_note', $email) && $email['send_delivery_note']) {
            $delivery = $this->generateDeliveryPDF($order);
        } else {
            $delivery = null;
        }

        $data = $this->prepareEmailData($order, $email);

        Mail::send($template, ['order' => $order, 'email' => $email, 'data' => $data], function ($m) use ($order, $status, $email, $to, $to_name, $cc, $bcc, $data, $invoice, $pdf, $delivery) {
            $m->from(config('chuckcms-module-ecommerce.emails.from_email'), config('chuckcms-module-ecommerce.emails.from_name'));
            
            $m->to($to, $to_name)->subject($data['subject']);

            if( $cc !== false && $cc !== null && $cc !== ''){
                $m->cc($cc);
            }

            if( $bcc !== false && $bcc !== null && $bcc !== ''){
                $m->bcc($bcc);
            }

            if ($invoice) {
                $m->attachData($pdf, $order->invoiceFileName, ['mime' => 'application/pdf']);
            }

            if ($delivery !== null) {
                $m->attachData($delivery, $order->deliveryFileName, ['mime' => 'application/pdf']);
            }
        });    
    }

    private function prepareEmailData(Order $order, array $email)
    {
        $data = [];
        $locale = app()->getLocale();

        foreach($email['data'] as $emailDataKey => $emailData) {
            $data[$emailDataKey] = $this->replaceEmailVariables($order, $emailData['value'][$locale]);
        }

        return $data;
    }

    private function replaceEmailVariables(Order $order, string $value)
    {
        $foundVariables = $this->getRawVariables($value, '[%', '%]');
        if (count($foundVariables) > 0) {
            foreach ($foundVariables as $foundVariable) {
                if (strpos($foundVariable, 'ORDER_NUMBER') !== false) {
                    $value = str_replace('[%ORDER_NUMBER%]', $order->json['order_number'], $value);
                }
                if (strpos($foundVariable, 'ORDER_SUBTOTAL') !== false) {
                    $value = str_replace('[%ORDER_SUBTOTAL%]', ChuckEcommerce::formatPrice($order->subtotal), $value);
                }
                if (strpos($foundVariable, 'ORDER_SUBTOTAL_TAX') !== false) {
                    $value = str_replace('[%ORDER_SUBTOTAL_TAX%]', ChuckEcommerce::formatPrice($order->subtotal_tax), $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING%]', ChuckEcommerce::formatPrice($order->shipping), $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_TAX') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_TAX%]', ChuckEcommerce::formatPrice($order->shipping_tax), $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_TOTAL') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_TOTAL%]', $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis', $value);
                }
                if (strpos($foundVariable, 'ORDER_TOTAL') !== false) {
                    $value = str_replace('[%ORDER_TOTAL%]', ChuckEcommerce::formatPrice($order->total), $value);
                }
                if (strpos($foundVariable, 'ORDER_TOTAL_TAX') !== false) {
                    $value = str_replace('[%ORDER_TOTAL_TAX%]', ChuckEcommerce::formatPrice($order->total_tax), $value);
                }
                if (strpos($foundVariable, 'ORDER_FINAL') !== false) {
                    $value = str_replace('[%ORDER_FINAL%]', ChuckEcommerce::formatPrice($order->final), $value);
                }
                if (strpos($foundVariable, 'ORDER_PAYMENT_METHOD') !== false) {
                    $value = str_replace('[%ORDER_PAYMENT_METHOD%]', $order->json['payment_method'], $value);
                }
                if (strpos($foundVariable, 'ORDER_SURNAME') !== false) {
                    $value = str_replace('[%ORDER_SURNAME%]', $order->surname, $value);
                }
                if (strpos($foundVariable, 'ORDER_NAME') !== false) {
                    $value = str_replace('[%ORDER_NAME%]', $order->name, $value);
                }
                if (strpos($foundVariable, 'ORDER_EMAIL') !== false) {
                    $value = str_replace('[%ORDER_EMAIL%]', $order->email, $value);
                }
                if (strpos($foundVariable, 'ORDER_TELEPHONE') !== false) {
                    $value = str_replace('[%ORDER_TELEPHONE%]', !is_null($order->tel) ? $order->tel : '', $value);
                }
                if (strpos($foundVariable, 'ORDER_COMPANY') !== false) {
                    $value = str_replace('[%ORDER_COMPANY%]', !is_null($order->json['company']['name']) ? $order->json['company']['name'] : '', $value);
                }
                if (strpos($foundVariable, 'ORDER_COMPANY_VAT') !== false) {
                    $value = str_replace('[%ORDER_COMPANY_VAT%]', !is_null($order->json['company']['name']) ? $order->json['company']['vat'] : '', $value);
                }
                if (strpos($foundVariable, 'ORDER_BILLING_STREET') !== false) {
                    $value = str_replace('[%ORDER_BILLING_STREET%]', $order->json['address']['billing']['street'], $value);
                }
                if (strpos($foundVariable, 'ORDER_BILLING_HOUSENUMBER') !== false) {
                    $value = str_replace('[%ORDER_BILLING_HOUSENUMBER%]', $order->json['address']['billing']['housenumber'], $value);
                }
                if (strpos($foundVariable, 'ORDER_BILLING_POSTALCODE') !== false) {
                    $value = str_replace('[%ORDER_BILLING_POSTALCODE%]', $order->json['address']['billing']['postalcode'], $value);
                }
                if (strpos($foundVariable, 'ORDER_BILLING_CITY') !== false) {
                    $value = str_replace('[%ORDER_BILLING_CITY%]', $order->json['address']['billing']['city'], $value);
                }
                if (strpos($foundVariable, 'ORDER_BILLING_COUNTRY') !== false) {
                    $value = str_replace('[%ORDER_BILLING_COUNTRY%]', config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['billing']['country'].'.native'), $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_STREET') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_STREET%]', $order->json['address']['shipping_equal_to_billing'] ? $order->json['address']['billing']['street'] : $order->json['address']['shipping']['street'], $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_HOUSENUMBER') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_HOUSENUMBER%]', $order->json['address']['shipping_equal_to_billing'] ? $order->json['address']['billing']['housenumber'] : $order->json['address']['shipping']['housenumber'], $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_POSTALCODE') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_POSTALCODE%]', $order->json['address']['shipping_equal_to_billing'] ? $order->json['address']['billing']['postalcode'] : $order->json['address']['shipping']['postalcode'], $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_CITY') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_CITY%]', $order->json['address']['shipping_equal_to_billing'] ? $order->json['address']['billing']['city'] : $order->json['address']['shipping']['city'], $value);
                }
                if (strpos($foundVariable, 'ORDER_SHIPPING_COUNTRY') !== false) {
                    $value = str_replace('[%ORDER_SHIPPING_COUNTRY%]', $order->json['address']['shipping_equal_to_billing'] ? config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['billing']['country'].'.native') : config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['shipping']['country'].'.native'), $value);
                }
                if (strpos($foundVariable, 'ORDER_PRODUCTS') !== false) {
                    $value = str_replace('[%ORDER_PRODUCTS%]', $this->formatProducts($order), $value);
                }
                if (strpos($foundVariable, 'ORDER_CARRIER_NAME') !== false) {
                    $value = str_replace('[%ORDER_CARRIER_NAME%]', $order->json['shipping']['name'], $value);
                }
                if (strpos($foundVariable, 'ORDER_CARRIER_TRANSIT_TIME') !== false) {
                    $value = str_replace('[%ORDER_CARRIER_TRANSIT_TIME%]', $order->json['shipping']['transit_time'], $value);
                }
            }
        }
        return $value;
    }

    private function formatProducts(Order $order) {
        $string = '';
        foreach($order->json['products'] as $sku => $product) {
            if(array_key_exists('_price', $product)) {
                $string .= '<p>'.$product['quantity'].'x "'.$product['title'];
                $string .= '" <small><b>('.ChuckEcommerce::formatPrice((float)$product['_price']['_unit']);
                if($order->hasDiscount) {
                    $string .= ') | '.ChuckEcommerce::formatPrice((float)$product['_price']['_total']);
                    $string .= ' - '.ChuckEcommerce::formatPrice((float)$product['_price']['_discount']);
                    $string .= ' => '.ChuckEcommerce::formatPrice((float)$product['_price']['_final']);
                } else {
                    $string .= ') => '.ChuckEcommerce::formatPrice((float)$product['_price']['_final']);
                }
                $string .= '</b></small><br><small>'.$product['options_text'].'</small>';
                $string .= (count($product['extras']) > 0 ? '<br><small>'.$product['extras_text'].'</small>' : '').'</p><hr>';
            } else {
                $string .= '<p>'.$product['quantity'].'x "'.$product['title'].' <small>'.$product['options_text'].'</small>" ('.ChuckEcommerce::formatPrice((float)$product['price_tax']).') => '.ChuckEcommerce::formatPrice((float)$product['total']).'</p><hr>';
            }
        }

        return $string;
    }

    private function getRawVariables($str, $startDelimiter, $endDelimiter) 
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
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

    private function generateDeliveryPDF(Order $order)
    {
        $pdf = PDF::loadView('chuckcms-module-ecommerce::pdf.delivery', compact('order'));
        return $pdf->output();
    }

    public function downloadDeliveryNote(Order $order)
    {
        $pdf = PDF::loadView('chuckcms-module-ecommerce::pdf.delivery', compact('order'));
        return $pdf->download($order->deliveryFileName);
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