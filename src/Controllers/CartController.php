<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Auth;
use ChuckCart;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Cart;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository;
use ChuckEcommerce;
use ChuckProduct;
use Str;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    private $productRepository;
    private $discountRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository, DiscountRepository $discountRepository)
    {
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;
    }

    public function overview()
    {
        $template = ChuckEcommerce::getTemplate();
    	return view($template->hintpath . '::templates.' . $template->slug . '.' . config('chuckcms-module-ecommerce.blade_layouts.cart_page'), compact('template'));
    }

    public function addToCart(Request $request)
    {
    	$product = $this->productRepository->get($request->product_id);

        //TODO: check if prod already in cart and check if still enough in stock
    	if($product == null || $this->productRepository->quantity($product, $request->sku) < $request->quantity ) {
    		$notification = $this->makeNotificationArray('error', ChuckProduct::title($product), 'kan niet worden toegevoegd!', 'icon-ban');
            return response()->json(['status' => 'error', 'notification' => $notification]);
    	}
    	
    	if(Auth::user()) {
    		ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

        foreach(ChuckCart::instance('shopping')->content() as $item) {
            if($item->id !== $request->sku) {
                continue;
            }

            if($this->productRepository->quantity($product, $request->sku) < ($request->quantity + $item->qty)) {
                $notification = $this->makeNotificationArray('error', ChuckProduct::title($product), 'te weinig stock beschikbaar!', 'icon-ban');
                return response()->json(['status' => 'error', 'notification' => $notification]);
            }

            if($this->compareOptions($item, $request->options) && $this->compareExtras($item, $request->extras)) {
                ChuckCart::instance('shopping')->update($item->rowId, (int)$request->quantity + $item->qty);

                if(Auth::user()) {
                    ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
                }

                $count = ChuckCart::instance('shopping')->count();
        
                $notification = $this->makeNotificationArray('success', ChuckProduct::title($product), 'succesvol toegevoegd!', 'icon-circle-check');
                return response()->json(['status' => 'success', 'notification' => $notification, 'cart_count' => $count]);
            }
        }
    	
    	ChuckCart::instance('shopping')->add(
                                $request->sku, 
                                ChuckProduct::title($product), 
                                (int)$request->quantity, 
                                ChuckProduct::priceRaw($product, $request->sku), 
                                ChuckProduct::getOptions($product, $request->sku, $request->options),
                                ChuckProduct::getExtras($product, $request->sku, $request->extras),
                                ChuckProduct::taxRateForSKU($product, $request->sku),
                                true
                            );

    	if(Auth::user()) {
    		ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

    	$count = ChuckCart::instance('shopping')->count();
        $notification = $this->makeNotificationArray('success', ChuckProduct::title($product), 'succesvol toegevoegd!', 'icon-circle-check');
    	return response()->json(['status' => 'success', 'notification' => $notification, 'cart_count' => $count]);
    }

    public function addCoupon(Request $request)
    {
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

        $notification = $this->makeNotificationArray('success', 'Coupon: '.$discount->code, 'succesvol toegevoegd!', 'icon-circle-check');

        $template = ChuckEcommerce::getTemplate();
        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

        return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }

    public function removeCoupon(Request $request)
    {
        $discount = $this->discountRepository->code($request->coupon);

        if(is_null($discount)) {
            return response()->json(['status' => 'undefined', 'status_text' => 'Coupon bestaat niet.']);
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

        $notification = $this->makeNotificationArray('warning', 'Coupon: '.$discount->code, 'verwijderd!', 'icon-circle-check');

        $template = ChuckEcommerce::getTemplate();

        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

        return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }

    public function removeFromCart(Request $request)
    {
        $product = $this->productRepository->sku($request->sku);

    	if(Auth::user()) {
    		ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

    	ChuckCart::instance('shopping')->remove($request->row_id);

    	if(Auth::user()) {
    		ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

        $notification = $this->makeNotificationArray('info', ChuckProduct::title($product), 'uit winkelwagen gehaald!', 'icon-circle-check');

        $template = ChuckEcommerce::getTemplate();

        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

    	return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }

    public function detailHtml(Request $request)
    {
        if(Auth::user()) {
            ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }

        $template = ChuckEcommerce::getTemplate();

        $view = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();

        $count = ChuckCart::instance('shopping')->count();

        if(Auth::user()) {
            ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
        }

        return response()->json(['status' => 'success', 'html' => $view, 'cart_count' => $count]);
    }

    public function updateCart(Request $request)
    {
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

    public function updateCartItem(Request $request)
    {
    	if(Auth::user()) {
    		ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

    	ChuckCart::instance('shopping')->update($request->row_id, $request->quantity);

        $notification = $this->makeNotificationArray('info', ChuckProduct::title(ChuckProduct::sku(ChuckCart::instance('shopping')->get($request->row_id)->id)), 'gewijzigd!', 'icon-circle-check');

    	if(Auth::user()) {
    		ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

        $template = ChuckEcommerce::getTemplate();

        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

    	return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
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
        if(is_null($options)) {
            return true;
        }
        
        foreach($options as $option) {
            $optionKey = Str::slug(explode('%|%', $option)[0], '_');
            
            if(!$item->options->has($optionKey)) {
                return false;
            }

            if($item->options->$optionKey !== explode('%|%', $option)[1]) {
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
        if(is_null($extras)) {
            return true;
        }
        
        foreach($extras as $extra) {
            $extraKey = explode('%|%', $extra)[0];
            
            if(!$item->extras->has($extraKey) && (int)explode('%|%', $extra)[1] > 0) {
                return false;
            }

            if($item->extras->has($extraKey) && (int)explode('%|%', $extra)[1] == 0) {
                return false;
            }

            $extraArray = $item->extras->$extraKey;
            if($item->extras->has($extraKey) && (int)$extraArray['qty'] !== (int)explode('%|%', $extra)[1]) {
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
            return response()->json(['status' => 'invalid', 'status_text' => 'Coupon is niet toegankelijk voor klantengroep.']);
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
