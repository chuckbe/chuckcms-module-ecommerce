<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use ChuckSite;
use Auth;
use Cart;
use Illuminate\Http\Request;

class CartRepository
{
	private $repeater;
    private $productRepository;

	public function __construct(Repeater $repeater, ProductRepository $productRepository)
    {
        $this->repeater = $repeater;
        $this->productRepository = $productRepository;
    }

    public function getProducts($cart)
    {
        $sku = [];
        foreach($cart->content() as $cartItem) {
            $sku[] = $cartItem->id;
        }
        
        return $this->productRepository->sku($sku);
    }

    public function formatProducts($products, $cart)
    {
        $product_array = [];
        foreach($cart->content() as $cartItem) {
            foreach($products as $productItem) {
                if ($this->productRepository->hasSKU($productItem, $cartItem->id)) {
                    $product_array[$cartItem->id]['options'] = $this->productRepository->getOptions($productItem, $cartItem->id, $cartItem->options);
                    $product_array[$cartItem->id]['options_text'] = $this->productRepository->getOptionsText($productItem, $cartItem->id, $cartItem->options);
                    $product_array[$cartItem->id]['price_object'] = $this->productRepository->priceObject($productItem, $cartItem->id);
                    $product_array[$cartItem->id]['sku'] = $cartItem->id;
                    $product_array[$cartItem->id]['title'] = $this->productRepository->title($productItem);
                    $product_array[$cartItem->id]['quantity'] = $cartItem->qty;
                    $product_array[$cartItem->id]['price'] = $cartItem->price;
                    $product_array[$cartItem->id]['price_tax'] = $cartItem->priceTax;
                    $product_array[$cartItem->id]['tax_rate'] = $cartItem->taxRate;
                    $product_array[$cartItem->id]['tax'] = $cartItem->tax;
                    $product_array[$cartItem->id]['tax_total'] = $cartItem->taxTotal;
                    $product_array[$cartItem->id]['subtotal'] = $cartItem->subtotal;
                    $product_array[$cartItem->id]['total'] = $cartItem->total;
                    break;
                }
            }
        }
        
        return $product_array;
    }

    public function allItemsInStock($products, $cart)
    {
        $fails = [];

        foreach($cart->content() as $cartItem) {
            foreach($products as $productItem) {
                if ($this->productRepository->hasSKU($productItem, $cartItem->id)) {
                    $product = $productItem;
                    break;
                }
            }
            $quantity = $this->productRepository->quantity($product, $cartItem->id);

            $options_string = $this->concatOptions($cartItem->options);

            if ($cartItem->qty > $quantity) { // && check if product is available through backorder
                $fails[] = array('sku' => $cartItem->id, 'name' => $this->productRepository->title($product) . ' (' . $options_string . ')', 'availability' => $quantity, 'type' => ($quantity < 1 ? 'out_of_stock' : 'end_of_stock'), 'cart_rowId' => $cartItem->rowId);
            }
        }

        if(count($fails) > 0) {
            return $fails;
        }

        return true;
    }

    public function subtractItemsFromStock($products, $cart) :bool
    {
        foreach($products as $product) {
            $json = $product->json;
            foreach($cart->content() as $cartItem) {
                if(!$this->productRepository->hasSKU($product, $cartItem->id)) {
                    continue;
                }

                if(!$this->productRepository->isCombination($product, $cartItem->id)) {
                    //subtract quantity from stock
                    $json['quantity'] = $json['quantity'] - $cartItem->qty;
                    continue;
                }

                $combination = $this->productRepository->combinationForSKU($product, $cartItem->id);
                $combinationKey = $this->productRepository->combinationKeyForSKU($product, $cartItem->id);

                $json['combinations'][$combinationKey]['quantity'] = $combination['quantity'] - $cartItem->qty;
            }

            $product->json = $json;
            if($product->update()) {
                continue;
            } else {
                //account for products' stock that was already updated
                return false;
            }
        }

        return true;
    }

    public function addItemsToStock($products, $cart) 
    {
        foreach($products as $product) {
            $json = $product->json;
            foreach($cart->content() as $cartItem) {
                if(!$this->productRepository->hasSKU($product, $cartItem->id)) {
                    continue;
                }

                if(!$this->productRepository->isCombination($product, $cartItem->id)) {
                    //subtract quantity from stock
                    $json['quantity'] = $json['quantity'] + $cartItem->qty;
                    continue;
                }

                $combination = $this->productRepository->combinationForSKU($product, $sku);
                $combinationKey = $this->productRepository->combinationKeyForSKU($product, $sku);

                $json['combinations'][$combinationKey]['quantity'] = $combination['quantity'] + $cartItem->qty;
            }

            $product->json = $json;
            $product->update();
        }
    }

    public function clear($cart)
    {
        if(Auth::check()) {
            Cart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }
        Cart::instance('shopping')->destroy();
        $cart->destroy();

        if(Auth::check()) {
            Cart::instance('shopping')->store('shopping_'.Auth::user()->id);
        }
    }

    public function updateUnavailableItemsInCart($cart, array $items)
    {
        foreach( $items as $item ) {
            if($item['availability'] == 0) {
                $cart->remove($item['cart_rowId']);
            } else {
                $cart->update($item['cart_rowId'], $item['availability']);
            }
        }

        return $cart;
    }

    private function concatOptions($options)
    {
        if(!is_array($options)) {
            $options = $options->toArray();
        }
        $string = '';
        for ($i=0; $i < count($options); $i++) { 
            $string = $string.array_keys($options)[$i] . ': ' . array_values($options)[$i];
            if(($i + 1) < count($options)) {
                $string = $string.', ';
            }
        }
        return $string;
    }

}