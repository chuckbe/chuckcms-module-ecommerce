<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\Chuckcms\Models\Module;
use App\Http\Controllers\Controller;
use ChuckCart;
use ChuckProduct;
use ChuckRepeater;
use ChuckEcommerce;

class POSController extends Controller
{
    private $customerRepository;
    private $discountRepository;
    private $locationRepository;
    private $productRepository;
	private $repeater;

	public function __construct(
        CustomerRepository $customerRepository,
        DiscountRepository $discountRepository,
        LocationRepository $locationRepository,
        ProductRepository $productRepository,
        Repeater $repeater)
    {
        $this->repeater = $repeater;
        $this->customerRepository = $customerRepository;
        $this->discountRepository = $discountRepository;
        $this->locationRepository = $locationRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $module = Module::where('slug', 'chuckcms-module-ecommerce')->first();
        $settings = $module->json['settings'];

        $locations = $this->locationRepository->getForUser(\Auth::user()->id);

        $customers = $this->customerRepository->get();

        $guest = $customers->where('email', 'guest@guest.com')->first();

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
}
