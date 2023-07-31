<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CartRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\PaymentRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Payment;

use App\Http\Controllers\Controller;

use ChuckCart;
use ChuckProduct;
use ChuckRepeater;
use ChuckEcommerce;

class POSController extends Controller
{
    private $cartRepository;
    private $customerRepository;
    private $discountRepository;
    private $locationRepository;
    private $orderRepository;
    private $paymentRepository;
    private $productRepository;
	private $repeater;

	public function __construct(
        CartRepository $cartRepository,
        CustomerRepository $customerRepository,
        DiscountRepository $discountRepository,
        LocationRepository $locationRepository,
        OrderRepository $orderRepository,
        PaymentRepository $paymentRepository,
        ProductRepository $productRepository,
        Repeater $repeater)
    {
        $this->repeater = $repeater;
        $this->cartRepository = $cartRepository;
        $this->customerRepository = $customerRepository;
        $this->discountRepository = $discountRepository;
        $this->locationRepository = $locationRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $module = Module::where('slug', 'chuckcms-module-ecommerce')->first();
        $settings = $module->json['settings'];

        $locations = $this->locationRepository->getForUser(\Auth::user()->id);

        $customers = $this->customerRepository->get();

        $guest = $this->customerRepository->guest();

        return view('chuckcms-module-ecommerce::pos.index', compact('settings', 'locations', 'customers', 'guest'));
    }


    public function combinations(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $product = ChuckRepeater::find($request['id']);

        if (is_null($product)) {
            return response()->json(['status' => 'error']);
        }

        $combinations = $product->json['combinations'];
        $options = $product->json['options'];
        $extras = $product->json['extras'];

        $availableStock = 0;

        foreach ($combinations as $combination) {
            $availableStock += $combination['quantity'];
        }

        if ($availableStock == 0) {
            $notification = $this->makeNotificationArray(
                'error',
                ChuckProduct::title($product),
                __('Te weinig stock beschikbaar!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification //replace with message
            ]);
        }

        $body = view(
            'chuckcms-module-ecommerce::pos.includes.modals.combinations_body',
            compact('combinations', 'options', 'extras')
        )->render();

        return response()->json(['status' => 'success', 'body' => $body]);
    }

    public function scanCode(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        $ean = $request['code'];

        $product = ChuckProduct::sku($ean);

        if (is_null($product) || !$product instanceof Repeater) {
            $notification = $this->makeNotificationArray(
                'error',
                $ean,
                __('Geen product teruggevonden!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification
            ]);
        }

        $sku = $product->json['code']['sku'];
        $combinations = $product->json['combinations'];
        $options = $product->json['options'];
        $extras = $product->json['extras'];

        $isCombination = $this->productRepository->isEanCombination($product, $ean);

        if ($isCombination) {
            $combination = $this->productRepository->combinationForEan($product, $ean);

            $sku = $combination['code']['sku'];
        }

        $availableStock = $this->productRepository->quantity($product, $sku);
        $quantity = 1;

        if ($availableStock == 0) {
            $notification = $this->makeNotificationArray(
                'error',
                ChuckProduct::title($product),
                __('Te weinig stock beschikbaar!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification //replace with message
            ]);
        }

        if (count($options) > 0 || count($extras) > 0) {
            $body = view(
                'chuckcms-module-ecommerce::pos.includes.modals.combinations_body',
                compact('combinations', 'options', 'extras')
            )->render();

            return response()->json([
                'status' => 'success',
                'body' => $body,
                'type' => 'combinations',
                'id' => $product->id
            ]);
        }

        foreach (ChuckCart::instance('pos')->content() as $item) {
            if ($item->id !== $sku) {
                continue;
            }

            if ($availableStock < ($quantity + $item->qty)) {
                $notification = $this->makeNotificationArray(
                    'error',
                    ChuckProduct::title($product),
                    __('Te weinig stock beschikbaar!'),
                    'warning'
                );

                return response()->json([
                    'status' => 'error',
                    'notification' => $notification //replace with message
                ]);
            }

            if ($this->compareOptions($item, $options) && $this->compareExtras($item, $extras)) {
                ChuckCart::instance('pos')->update($item->rowId, (int)$quantity + $item->qty);

                $guest = $this->customerRepository->guest();

                $cart = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

                return response()->json([
                    'status' => 'success',
                    'cart' => $cart,
                    'type' => 'cart'
                ]);
            }
        }

        ChuckCart::instance('pos')->add(
            $sku,
            ChuckProduct::title($product),
            (int)$quantity,
            ChuckProduct::priceRaw($product, $sku),
            ChuckProduct::getOptions($product, $sku, $options),
            ChuckProduct::getExtras($product, $sku, $extras),
            ChuckProduct::taxRateForSKU($product, $sku),
            true
        );

        $guest = $this->customerRepository->guest();

        $cart = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

        return response()->json([
            'status' => 'success',
            'cart' => $cart,
            'type' => 'cart'
        ]);
    }

    public function addToCart(Request $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $id = $request->get('id');
        $sku = $request->get('sku');
        $options = $request->get('options');
        $extras = $request->get('extras');
        $quantity = $request->get('quantity');

        $product = $this->productRepository->get($id);

        $availableStock = $this->productRepository->quantity($product, $sku);

        if ($product == null) {
            $notification = $this->makeNotificationArray(
                'error',
                ChuckProduct::title($product),
                __('Product niet teruggevonden!'),
                'error'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification //replace with message
            ]);
        }

        if ($availableStock < $quantity) {
            $notification = $this->makeNotificationArray(
                'error',
                ChuckProduct::title($product),
                __('Te weinig stock beschikbaar!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification //replace with message
            ]);
        }

        foreach (ChuckCart::instance('pos')->content() as $item) {
            if ($item->id !== $sku) {
                continue;
            }

            if ($availableStock < ($quantity + $item->qty)) {
                $notification = $this->makeNotificationArray(
                    'error',
                    ChuckProduct::title($product),
                    __('Te weinig stock beschikbaar!'),
                    'warning'
                );

                return response()->json([
                    'status' => 'error',
                    'notification' => $notification //replace with message
                ]);
            }

            if ($this->compareOptions($item, $options) && $this->compareExtras($item, $extras)) {
                ChuckCart::instance('pos')->update($item->rowId, (int)$quantity + $item->qty);

                $guest = $this->customerRepository->guest();

                $cart = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

                return response()->json([
                    'status' => 'success',
                    'cart' => $cart
                ]);
            }
        }

        ChuckCart::instance('pos')->add(
            $sku,
            ChuckProduct::title($product),
            (int)$quantity,
            ChuckProduct::priceRaw($product, $sku),
            ChuckProduct::getOptions($product, $sku, $options),
            ChuckProduct::getExtras($product, $sku, $extras),
            ChuckProduct::taxRateForSKU($product, $sku),
            true
        );

        $guest = $this->customerRepository->guest();

        $cart = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ]);
    }

    public function updateCartItem(Request $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $sku = $request->get('sku');
        $rowId = $request->get('row_id');
        $quantity = $request->get('quantity');

        $product = $this->productRepository->sku($sku);

        $availableStock = $this->productRepository->quantity($product, $sku);

        if ($availableStock < $quantity) {
            $notification = $this->makeNotificationArray(
                'error',
                ChuckProduct::title($product),
                __('Te weinig stock beschikbaar!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification
            ]);
        }

        ChuckCart::instance('pos')->update($rowId, $quantity);

        $guest = $this->customerRepository->guest();

        $cart = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ]);
    }

    public function removeCartItem(Request $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $product = $this->productRepository->sku($request->sku);

        ChuckCart::instance('pos')->remove($request->row_id);

        $notification = $this->makeNotificationArray(
            'info',
            ChuckProduct::title($product),
            __('uit winkelwagen gehaald!'),
            'info'
        );

        $guest = $this->customerRepository->guest();

        $cart = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

        return response()->json([
            'status' => 'success',
            'cart' => $cart,
            'notification' => $notification
        ]);
    }

    public function initiatePayment(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'location' => 'required'
        ]);

        $customer = $this->customerRepository->find($request->get('customer_id'));

        $cart = ChuckCart::instance('pos');

        $products = $this->cartRepository->getProducts($cart);

        $in_stock = $this->cartRepository->allItemsInStock($products, $cart);

        if (is_array($in_stock) && $in_stock !== true && count($in_stock) > 0) { // check if all items are in stock and available for order
            $cart = $this->cartRepository->updateUnavailableItemsInCart($cart, $in_stock);

            $notification = $this->makeUnavailableNotification($in_stock);

            $guest = $this->customerRepository->guest();

            $cart_view = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

            return response()->json([
                'status' => 'availability',
                'notification' => $notification,
                'cart' => $cart_view
            ]);
        }

        if (!$this->cartRepository->subtractItemsFromStock($products, $cart)) { // subtract products quantity from stock
            $notification = $this->makeNotificationArray('waring', 'Er is iets misgegaan...', 'Probeer het later nog eens!', 'warning');

            return response()->json(['status' => 'subtraction', 'notification' => $notification]);
        }

        $location = Repeater::find($request->get('location'));

        $order = $this->orderRepository->pos($cart, $products, $customer, $location); // save order in the database

        if ($order == null) { //failed > addItemsToStock
            $products = $this->cartRepository->getProducts($cart);
            $this->cartRepository->addItemsToStock($products, $cart);

            $notification = $this->makeNotificationArray(
                'waring',
                'Er is iets misgegaan...',
                'Probeer het later nog eens!',
                'warning'
            );

            return response()->json(['status' => 'failed', 'notification' => $notification]);
        }

        $body = view('chuckcms-module-ecommerce::pos.includes.modals.payment_modal', compact('order'))->render();

        return response()->json(['status' => 'success', 'body' => $body, 'order' => $order]);
    }

    public function cashPayment(Request $request)
    {
        $this->validate($request, [
            'order' => 'required',
            'amount' => 'required',
        ]);

        $order = $this->orderRepository->find($request->get('order'));

        if (is_null($order)) {
            $notification = $this->makeNotificationArray(
                'warning',
                'Bestelling',
                __('Deze bestelling bestaat niet!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification
            ]);
        }

        $payment = $this->paymentRepository->cash($request->get('amount'), $order);

        return response()->json([
            'status' => 'success',
            'payment' => $payment
        ]);
    }

    public function terminalPayment(Request $request)
    {
        $this->validate($request, [
            'order' => 'required',
            'amount' => 'required',
            'location' => 'required',
        ]);

        $order = $this->orderRepository->find($request->get('order'));

        if (is_null($order)) {
            $notification = $this->makeNotificationArray(
                'warning',
                'Bestelling',
                __('Deze bestelling bestaat niet!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification
            ]);
        }

        $location = Repeater::find($request->get('location'));

        if (is_null($location) || is_null($location->mollie_terminal_id)) {
            $notification = $this->makeNotificationArray(
                'warning',
                'Locatie',
                __('niet gevonden of geen terminal actief!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification
            ]);
        }

        //@TODO: check if terminal is actually active

        $payment = $this->paymentRepository->terminal($request->get('amount'), $location, $order);

        return response()->json([
            'status' => 'success',
            'payment' => $payment
        ]);
    }

    public function checkTerminalPayment(Request $request)
    {
        $this->validate($request, [
            'payment' => 'required'
        ]);

        $payment = Payment::find($request->get('payment'));

        $status = $this->paymentRepository->checkTerminal($payment);

        return response()->json([
            'status' => $status
        ]);
    }

    public function removePayment(Request $request)
    {
        $this->validate($request, [
            'payment' => 'required'
        ]);

        $payment = Payment::find($request->get('payment'));

        if ($payment->delete()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function cancelOrder(Request $request)
    {
        $this->validate($request, [
            'order' => 'required'
        ]);

        $order = $this->orderRepository->find($request->get('order'));

        if (is_null($order)) {
            $notification = $this->makeNotificationArray(
                'warning',
                'Bestelling',
                __('Deze bestelling bestaat niet!'),
                'warning'
            );

            return response()->json([
                'status' => 'error',
                'notification' => $notification
            ]);
        }

        $payments = $order->payments;

        $needs_refund = false;

        foreach ($payments as $payment) {
            if ($payment->type = "pointofsale" && $payment->status == "paid") {
                $needs_refund = true; //@TODO: do something with this!
            }
        }

        $cart = ChuckCart::instance('pos');

        $products = $this->cartRepository->getProducts($cart);
        $this->cartRepository->addItemsToStock($products, $cart);

        $this->orderRepository->updateStatus($order, 'canceled');

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function order(Request $request)
    {
        $this->validate($request, [
            'order' => 'required',
            'change' => 'required'
        ]);

        $cart = ChuckCart::instance('pos');

        $order = $this->orderRepository->find($request->get('order'));

        if ($request->get('change') < 0) {
            $payment = $this->paymentRepository->change($request->get('change'), $order);
        }

        $this->orderRepository->updateStatus($order, 'payment');

        ChuckCart::instance('pos')->destroy(); // clear the cart

        $order->refresh();

        $payments = $order->payments;

        $guest = $this->customerRepository->guest();
        $cartView = view('chuckcms-module-ecommerce::pos.cart.index', compact('guest'))->render();

        return response()->json([
            'status' => 'success',
            'cart' => $cartView,
            'order' => $order,
            'payments' => $payments
        ]);
    }












    public function addCoupon(Request $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $discount = $this->discountRepository->code($request->coupon);
        $user = Auth::user();

        if($user) {
            $cart = ChuckCart::instance('shopping')->restore('shopping_'.$user->id);
        } else {
            $cart = ChuckCart::instance('shopping');
        }

        if(!ChuckCart::validateDiscount($request->coupon, $user)) {
            return $this->getDiscountValidationResponse($request->coupon, $cart, $user);
        }

        $cart->addDiscount($discount->code, $discount->type, $discount->value, (int)$discount->priority);

        if($user) {
            $cart->store('shopping_'.$user->id);
        }

        $notification = $this->makeNotificationArray('success', 'Coupon: '.$discount->code, __('succesvol toegevoegd!'), 'icon-circle-check');

        $template = ChuckEcommerce::getTemplate();
        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

        return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }

    public function removeCoupon(Request $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        $discount = $this->discountRepository->code($request->coupon);

        if(is_null($discount)) {
            return response()->json(['status' => 'undefined', 'status_text' => __('Coupon bestaat niet.')]);
        }

        if(Auth::user()) {
            $cart = ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        } else {
            $cart = ChuckCart::instance('shopping');
        }

        $cart->removeDiscount($discount->code);

        if(Auth::user()) {
            $cart->store('shopping_'.Auth::user()->id);
        }

        $notification = $this->makeNotificationArray('warning', 'Coupon: '.$discount->code, __('verwijderd!'), 'icon-circle-check');

        $template = ChuckEcommerce::getTemplate();

        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

        return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }




    public function updateCart(Request $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->lang);
        }

        if(Auth::user()) {
            ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }

        $view = view('cart._cart_table')->render();

        $count = ChuckCart::instance('shopping')->count();

        if(Auth::user()) {
            ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
        }

        return response()->json(['status' => 'success', 'html_table' => $view, 'cart_count' => $count]);
    }





    /**
     * Compare the given options to the options of an existing cartitem.
     *
     * @param  CartItem $item
     * @param  array|null $options
     * @return bool
     */
    public function compareOptions($item, $options)
    {
        if (is_null($options)) {
            return true;
        }

        foreach ($options as $option) {
            $optionKey = Str::slug(explode('%|%', $option)[0], '_');

            if (!$item->options->has($optionKey)) {
                return false;
            }

            if ($item->options->$optionKey !== explode('%|%', $option)[1]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Compare the given extras to the extras of an existing cartitem.
     *
     * @param  CartItem $item
     * @param  array|null $extras
     * @return bool
     */
    public function compareExtras($item, $extras)
    {
        if (is_null($extras)) {
            return true;
        }

        foreach ($extras as $extra) {
            $extraKey = explode('%|%', $extra)[0];

            if (! $item->extras->has($extraKey) && (int)explode('%|%', $extra)[1] > 0) {
                return false;
            }

            if ($item->extras->has($extraKey) && (int)explode('%|%', $extra)[1] == 0) {
                return false;
            }

            $extraArray = $item->extras->$extraKey;
            if ($item->extras->has($extraKey) && (int)$extraArray['qty'] !== (int)explode('%|%', $extra)[1]) {
                return false;
            }
        }

        return true;
    }

    private function getDiscountValidationResponse(string $couponCode, Cart $cart, $user)
    {
        $discount = $this->discountRepository->code($couponCode);

        if(is_null($discount)) {
            return response()->json(['status' => 'undefined', 'status_text' => 'Coupon werd niet teruggevonden.']);
        }

        if(! $this->discountRepository->checkValidity($discount)) {
            return response()->json(['status' => 'invalid', 'status_text' => 'Coupon is niet geldig.']);
        }

        if(! $this->discountRepository->checkMinima($discount, $cart)) {
            $status_text = 'Voor deze coupon moet er minimaal € '.$discount->minimum.' in uw winkelwagen zijn.';
            return response()->json(['status' => 'invalid', 'status_text' => $status_text]);
        }

        if(! $this->discountRepository->checkAvailability($discount)) {
            return response()->json(['status' => 'invalid', 'status_text' => 'Coupon is niet meer geldig.']);
        }

        if($user) {
            if(! $this->discountRepository->checkAvailabilityForCustomer($discount, $user->id)) {
                return response()->json(['status' => 'invalid', 'status_text' => 'Coupon is reeds gebruikt.']);
            }
        }

        if(! $this->discountRepository->checkAvailabilityForCustomerGroup($discount, $user)) {
            return response()->json(['status' => 'invalid', 'status_text' => 'Coupon is niet toegankelijk voor klant of klantengroep.']);
        }

        if(! $this->discountRepository->checkConditions($discount, $cart)) {
            return response()->json(['status' => 'invalid', 'status_text' => 'Coupon is niet toepasbaar voor winkelwagen.']);
        }
    }

    /**
     * Make an notification array.
     *
     * @param  string $type
     * @param  string $title
     * @param  string $message
     * @param  string $icon
     * @return array
     */
    private function makeNotificationArray(string $type, string $title, string $message, string $icon)
    {
        return array(
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon
        );
    }

    /**
     * Make unavailable items notification array.
     *
     * @param  array $items
     * @return array
     */
    private function makeUnavailableNotification($items)
    {
        $type = 'warning';
        $icon = 'warning';
        $title = 'Niet alle producten zijn beschikbaar';

        $message = 'Volgende producten zijn niet beschikbaar: <br>';

        foreach ($items as $key => $item) {
            if ($key > 0) {
                $message .= '<br>';
            }
            if ($item['type'] == 'out_of_stock') {
                $message .= '[GEEN STOCK]: ';
            } else {
                $message .= '[TE WEINIG STOCK]: ';
            }

            $message .= $item['title'];

            $message .= ' - ' . $item['availability'] . ' beschikbaar';
        }

        return array(
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon
        );
    }
}
