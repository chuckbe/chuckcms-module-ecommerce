<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Auth;
use Cart;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use ChuckEcommerce;
use ChuckProduct;
use Str;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    private $productRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function overview()
    {
        $template = ChuckEcommerce::getTemplate();
    	return view($template->hintpath . '::templates.' . $template->slug . '.' . config('chuckcms-module-ecommerce.blade_layouts.cart_page'), compact('template'));
    }

    public function addToCart(Request $request)
    {
        //dd($request->sku);

    	$product = $this->productRepository->get($request->product_id);

        //TODO: check if prod already in cart and check if still enough in stock
    	if($product == null || $this->productRepository->quantity($product, $request->sku) < $request->quantity ) {
    		$notification = [];
            $notification['type'] = 'error';
            $notification['title'] = ChuckProduct::title($product);
            $notification['message'] = 'kan niet worden toegevoegd!';
            $notification['icon'] = 'icon-ban';

            return response()->json(['status' => 'error', 'notification' => $notification]);
    	}
    	
    	if(Auth::user()) {
    		Cart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

        foreach(Cart::instance('shopping')->content() as $item) {
            if($item->id == $request->sku) {

                if($this->productRepository->quantity($product, $request->sku) < ($request->quantity + $item->qty)) {
                    $notification = [];
                    $notification['type'] = 'error';
                    $notification['title'] = ChuckProduct::title($product);
                    $notification['message'] = 'te weinig stock beschikbaar!';
                    $notification['icon'] = 'icon-ban';

                    return response()->json(['status' => 'error', 'notification' => $notification]);
                }

                if($this->compareOptions($item, $request->options)) {
                    Cart::instance('shopping')->update($item->rowId, (int)$request->quantity + $item->qty);

                    if(Auth::user()) {
                        Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
                    }

                    $count = Cart::instance('shopping')->count();
            
                    $notification = [];
                    $notification['type'] = 'success';
                    $notification['title'] = ChuckProduct::title($product);
                    $notification['message'] = 'succesvol toegevoegd!';
                    $notification['icon'] = 'icon-circle-check';

                    return response()->json(['status' => 'success', 'notification' => $notification, 'cart_count' => $count]);
                }
            }
        }
    	
    	Cart::instance('shopping')->add(
                                $request->sku, 
                                ChuckProduct::title($product), 
                                (int)$request->quantity, 
                                ChuckProduct::priceNoTaxRaw($product, $request->sku), 
                                ChuckProduct::getOptions($product, $request->sku, $request->options),
                                ChuckProduct::taxRateForSKU($product, $request->sku)
                            );

    	if(Auth::user()) {
    		Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

    	$count = Cart::instance('shopping')->count();
        
        $notification = [];
        $notification['type'] = 'success';
        $notification['title'] = ChuckProduct::title($product);
        $notification['message'] = 'succesvol toegevoegd!';
        $notification['icon'] = 'icon-circle-check';

    	return response()->json(['status' => 'success', 'notification' => $notification, 'cart_count' => $count]);
    }

    public function removeFromCart(Request $request)
    {
        $product = $this->productRepository->sku($request->sku);

    	if(Auth::user()) {
    		Cart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

    	Cart::instance('shopping')->remove($request->row_id);

    	if(Auth::user()) {
    		Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

        $notification = [];
        $notification['type'] = 'info';
        $notification['title'] = ChuckProduct::title($product);
        $notification['message'] = 'uit winkelwagen gehaald!';
        $notification['icon'] = 'icon-circle-check';

        $template = ChuckEcommerce::getTemplate();

        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

    	return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }

    public function detailHtml(Request $request)
    {
        if(Auth::user()) {
            Cart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }

        $template = ChuckEcommerce::getTemplate();

        $view = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();

        $count = Cart::instance('shopping')->count();

        if(Auth::user()) {
            Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
        }

        return response()->json(['status' => 'success', 'html' => $view, 'cart_count' => $count]);
    }

    public function updateCart(Request $request)
    {
    	if(Auth::user()) {
    		Cart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

    	$view = view('cart._cart_table')->render();

    	$count = Cart::instance('shopping')->count();

    	if(Auth::user()) {
    		Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

    	return response()->json(['status' => 'success', 'html_table' => $view, 'cart_count' => $count]);
    }

    public function updateCartItem(Request $request)
    {
    	if(Auth::user()) {
    		Cart::instance('shopping')->restore('shopping_'.Auth::user()->id);
    	}

    	Cart::instance('shopping')->update($request->row_id, $request->quantity);

        $notification = [];
        $notification['type'] = 'info';
        $notification['title'] = ChuckProduct::title(ChuckProduct::sku(Cart::instance('shopping')->get($request->row_id)->id));
        $notification['message'] = 'gewijzigd!';
        $notification['icon'] = 'icon-circle-check';

    	if(Auth::user()) {
    		Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
    	}

        $template = ChuckEcommerce::getTemplate();

        $view_detail = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_detail'))->render();
        $view_overview = view($template->hintpath . '::templates.' . $template->slug . '.ecommerce.' . config('chuckcms-module-ecommerce.blade_layouts.cart_overview'))->render();

    	return response()->json(['status' => 'success', 'notification' => $notification, 'html_overview' => $view_overview, 'html_detail' => $view_detail]);
    }

    public function compareOptions($item, $options) 
    {
        $bool = false;
        foreach($options as $given_option) {
            $key = Str::slug(explode('%|%', $given_option)[0], '_');
            if($item->options->has($key)) {
                if($item->options->$key == explode('%|%', $given_option)[1]) {
                    $bool = true;
                }
            } 
        }
        return $bool;
    }
}
