<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use ChuckEcommerce;
use Mollie;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderStatusWasUpdated;

class OrderController extends Controller
{
    private $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $orders = Order::where('status', '!=', 'new')->get();
        return view('chuckcms-module-ecommerce::backend.orders.index', ['orders' => $orders]);
    }

    public function detail(Order $order)
    {
        return view('chuckcms-module-ecommerce::backend.orders.detail', ['order' => $order]);
    }

    public function status(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required',
            'order_status' => 'required|in:'.implode(',', array_keys(ChuckEcommerce::getSetting('order.statuses'))) 
        ]);

        $order = Order::where('id', $request->order_id)->first();
        event(new OrderStatusWasUpdated($order, $request->order_status));

        return redirect()->route('dashboard.module.ecommerce.orders.detail', ['order' => $order]);
    }

    public function invoice(Order $order)
    {
        return $this->orderRepository->downloadInvoice($order);
    }

    public function webhookMollie(Request $request)
    {
        if (! $request->has('id')) {
            return;
        }

        $payment = Mollie::api()->payments()->get($request->id);
        
        $order = Order::where('id', $payment->metadata->order_id)->where('json->order_number', $payment->metadata->order_number)->first();
        
        if($order == null) {
            return response()->json(['status' => 'success'], 200);
        }

        if(ChuckEcommerce::getSetting('order.statuses.'.$order->status.'.paid')) {
            return response()->json(['status' => 'success'], 200);
        }

        if ($payment->isPaid()) { 
            event(new OrderWasPaid($order));
        } else {
            if($order->json['status'] !== Order::STATUS_PAYMENT) { // fix this for incoming refund webhooks 
                $json = $order->json;
                $json['status'] = $payment->status;
                $order->json = $json;
                $order->save();
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
