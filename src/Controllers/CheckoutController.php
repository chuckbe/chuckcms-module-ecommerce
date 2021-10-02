<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Auth;
use ChuckCart;
use ChuckEcommerce;
use ChuckProduct;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CartRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\PaymentRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\UserRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Requests\PlaceOrderRequest;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;
use URL;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    private $cartRepository;
    private $customerRepository;
    private $orderRepository;
    private $paymentRepository;
    private $productRepository;
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( 
        CartRepository $cartRepository, 
        CustomerRepository $customerRepository, 
        OrderRepository $orderRepository, 
        PaymentRepository $paymentRepository, 
        ProductRepository $productRepository, 
        UserRepository $userRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->customerRepository = $customerRepository;
        $this->paymentRepository = $paymentRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if (Auth::user()) {
            ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }

        if (ChuckCart::instance('shopping')->content()->count() == 0) {
            return redirect()->route('module.ecommerce.cart.overview');
        }

        if (ChuckCart::instance('shopping')->total() < ChuckEcommerce::getSetting('order.minimum')) {
            return redirect()->route('module.ecommerce.cart.overview');
        }

        $template = ChuckEcommerce::getTemplate();
        return view('chuckcms-module-ecommerce::checkout.index', compact('template'));
    }

    public function placeOrder(PlaceOrderRequest $request)
    {
        // make the user / customer if needed — and — send the email
        if (!Auth::check() && !(bool)$request->get('check_out_as_guest')) {
            $user = $this->userRepository->create($request);
            Auth::login($user);
            $customer = $this->customerRepository->createFromUser($user, $request);
            ChuckCart::instance('shopping')->store('shopping_'.$user->id);
            //send email for customer creation
        }  elseif (!Auth::check() && (bool)$request->get('check_out_as_guest')) {
            $customer = $this->customerRepository->createFromRequest($request);
        } elseif(Auth::check()) {
            $user = Auth::user();
            ChuckCart::instance('shopping')->restore('shopping_'.$user->id);
            $customer = $user->customer;
        }

        $cart = ChuckCart::instance('shopping');

        $products = $this->cartRepository->getProducts($cart);

        $in_stock = $this->cartRepository->allItemsInStock($products, $cart);
        
        if (is_array($in_stock) && $in_stock !== true && count($in_stock) > 0) { // check if all items are in stock and available for order
            $cart = $this->cartRepository->updateUnavailableItemsInCart($cart, $in_stock);
            if ($user) {
                $cart->store('shopping_'.$user->id);
            }

            return response()->json(['status' => 'availability', 'notification' => $in_stock]);
        } 


        if (!$this->cartRepository->subtractItemsFromStock($products, $cart)) { // subtract products quantity from stock
            return response()->json(['status' => 'subtraction']);
        }

        $order = $this->orderRepository->new($request, $cart, $products, $customer); // save order in the database
        
        if ($order == null) { //failed > addItemsToStock
            $products = $this->cartRepository->getProducts($cart);
            $this->cartRepository->addItemsToStock($products, $cart);

            return response()->json(['status' => 'failed', 'notification' => 'blabla']);
        }


        $payment = $this->paymentRepository->initiate($order); // make payment and add details on order
        if ($payment == null) { //failed > addItemsToStock
            $this->orderRepository->revert($order);

            $products = $this->cartRepository->getProducts($cart);
            $this->cartRepository->addItemsToStock($products, $cart);
            
            return response()->json(['status' => 'payment_error', 'notification' => 'blabla']);
        }


        $this->cartRepository->clear($cart); // clear the cart

        $url = $payment->getCheckoutUrl(); // return payment url

        return response()->json(['status' => 'success', 'url' => $url]);
    }

    public function orderFollowup($order_number)
    {
        $order = Order::where('json->order_number', $order_number)->first();

        if(!$order->isPaid()) {
            $this->paymentRepository->check($order);
        }

        if($order == null) {
            return abort(404);
        } else {
            return redirect(URL::to(config('chuckcms-module-ecommerce.order.redirect_url.'.$order->json['lang'])), 303)->with('order_number', $order_number);
        }

    }

    public function orderStatus(Request $request)
    {
        $order_number = $request['order_number'];
        $order = Order::where('json->order_number', $order_number)->first();
        
        return response()->json([
            'status' => ChuckEcommerce::getSetting('order.statuses.'.$order->status)
        ]);     
    }

    public function orderPay($order_number)
    {
        $order = Order::where('json->order_number', $order_number)->first();
        
        if($order->isPaid()) {
            return redirect()->route('module.ecommerce.checkout.followup', ['order_number' => $order_number]);
        }

        $payment = $this->paymentRepository->initiate($order); // make payment and add details on order

        $url = $payment->getCheckoutUrl(); // return payment url

        return redirect($url, 303);
    }
}