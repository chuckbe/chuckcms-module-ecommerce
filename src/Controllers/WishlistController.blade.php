<?php

namespace App\Http\Controllers;

use App\Product;
use App\Model;
use Auth;
use ChuckCart;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function overview()
    {
    	return view('wishlist.overview');
    }

    public function addToWishlist(Request $request)
    {
    	$product = Product::where('id', $request->product_id)->first();
    	$model = Model::where('base_spec_code', $request->base_spec_code)->first();

    	if($product == null || $model == null || !$product->hasCarModel($request->base_spec_code)) {
    		return response()->json(['status' => 'error', 'notification' => __('wishlist.error')]);
    	}

        if(Auth::user()) {
            ChuckCart::instance('wishlist')->restore('wishlist_'.Auth::user()->id);
        }
    	
    	ChuckCart::instance('wishlist')->add($product, 1, ['model' => $model->base_spec_code]);

        if(Auth::user()) {
            ChuckCart::instance('wishlist')->store('wishlist_'.Auth::user()->id);
        }

        $count = ChuckCart::instance('wishlist')->count();

    	return response()->json(['status' => 'success', 'notification' => __('wishlist.added', ['item' => $product->accessory_name]), 'wishlist_count' => $count, 'product_id' => $product->id]);
    }

    public function removeFromWishlist(Request $request)
    {
        if(Auth::user()) {
            ChuckCart::instance('wishlist')->restore('wishlist_'.Auth::user()->id);
        }

        $product_id = ChuckCart::instance('wishlist')->get($request->row_id)->model->id;
    	ChuckCart::instance('wishlist')->remove($request->row_id);

        if(Auth::user()) {
            ChuckCart::instance('wishlist')->store('wishlist_'.Auth::user()->id);
        }

    	return response()->json(['status' => 'success', 'notification' => __('wishlist.removed'), 'product_id' => $product_id]);
    }

    public function updateWishlist(Request $request)
    {
        if(Auth::user()) {
            ChuckCart::instance('wishlist')->restore('wishlist_'.Auth::user()->id);
        }

    	$view = view('wishlist._wishlist_table')->render();

        $count = ChuckCart::instance('wishlist')->count();

        if(Auth::user()) {
            ChuckCart::instance('wishlist')->store('wishlist_'.Auth::user()->id);
        }
    	
    	return response()->json(['status' => 'success', 'html_table' => $view, 'wishlist_count' => $count]);
    }

    public function updateWishlistLink(Request $request)
    {
        $product = Product::find($request->product_id);
        $view = view('wishlist._wishlist_link_block', compact('product'))->render();
        
        return response()->json(['status' => 'success', 'html_link' => $view]);
    }

    public function updateWishlistItem(Request $request)
    {
        if(Auth::user()) {
            ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }

    	if($request->add == "addition"){
    		$qty = ChuckCart::instance('wishlist')->get($request->row_id)->qty + 1;
    	} else {
    		$qty = ChuckCart::instance('wishlist')->get($request->row_id)->qty - 1;
    	}
    	ChuckCart::instance('wishlist')->update($request->row_id, $qty);

        if(Auth::user()) {
            ChuckCart::instance('wishlist')->store('wishlist_'.Auth::user()->id);
        }

    	return response()->json(['status' => 'success', 'notification' => __('wishlist.updated')]);
    }
}
