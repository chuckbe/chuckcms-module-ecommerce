<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use ChuckSite;
use Auth;
use ChuckCart;
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
        if (count($cart->content()) == 1) {
            $sku = $cart->content()->first()->id;
            return $this->productRepository->sku($sku);
        }

        foreach($cart->content() as $cartItem) {
            $sku[] = $cartItem->id;
        }
        
        return $this->productRepository->sku($sku);
    }

    public function formatProducts($products, $cart)
    {
        $product_array = [];
        $cartItems = $cart->content();
        foreach($cartItems as $cartKey => $cartItem) {
            foreach($products as $productItem) {
                if ($this->productRepository->hasSKU($productItem, $cartItem->id)) {
                    $product_array[$cartItem->rowId]['options'] = $this->productRepository->getOptions($productItem, $cartItem->id, $cartItem->options);
                    $product_array[$cartItem->rowId]['options_text'] = $this->productRepository->getOptionsText($productItem, $cartItem->id, $cartItem->options);
                    $product_array[$cartItem->rowId]['extras'] = $this->productRepository->getExtras($productItem, $cartItem->id, $cartItem->extras);
                    $product_array[$cartItem->rowId]['extras_text'] = $this->productRepository->getExtrasText($productItem, $cartItem->id, $cartItem->extras);
                    $product_array[$cartItem->rowId]['discounts'] = $cartItem->discounts->toArray();
                    
                    $product_array[$cartItem->rowId]['_price'] = $this->priceObject($cartItem);
                    
                    $product_array[$cartItem->rowId]['sku'] = $cartItem->id;
                    $product_array[$cartItem->rowId]['title'] = $this->productRepository->title($productItem);

                    $product_array[$cartItem->rowId]['price'] = $cartItem->price;
                    $product_array[$cartItem->rowId]['tax_rate'] = $cartItem->taxRate;
                    $product_array[$cartItem->rowId]['quantity'] = $cartItem->qty;
                    $product_array[$cartItem->rowId]['total'] = $cartItem->total;
                    $product_array[$cartItem->rowId]['discount'] = $cartItem->_discount;
                    $product_array[$cartItem->rowId]['tax'] = $cartItem->tax;
                    $product_array[$cartItem->rowId]['final'] = $cartItem->final;

                    break;
                }
            }
        }
        
        return $product_array;
    }

    public function priceObject($cartItem)
    {
        $array = [];

        $array['_unit_base']        = $cartItem->_unit_base;
        $array['_unit_extras']      = $cartItem->_unit_extras;
        $array['_unit_raw']         = $cartItem->_unit_raw;
        $array['_unit']             = $cartItem->_unit;

        $array['_total_base']       = $cartItem->_total_base;
        $array['_total_extras']     = $cartItem->_total_extras;
        $array['_total_raw']        = $cartItem->_total_raw;
        $array['_total']            = $cartItem->_total;

        $array['_discount_base']    = $cartItem->_discount_base;
        $array['_discount_extras']  = $cartItem->_discount_extras;
        $array['_discount_raw']     = $cartItem->_discount_raw;
        $array['_discount']         = $cartItem->_discount;

        $array['_final_base']       = $cartItem->_final_base;
        $array['_final_extras']     = $cartItem->_final_extras;
        $array['_final_raw']        = $cartItem->_final;
        $array['_final']            = $cartItem->_final;

        $array['_tax_base']         = $cartItem->_tax_base;
        $array['_tax_base_raw']     = $cartItem->_tax_base_raw;
        $array['_tax_extras']       = $cartItem->_tax_extras;
        $array['_tax_extras_raw']   = $cartItem->_tax_extras_raw;
        $array['_tax_raw']          = $cartItem->_tax_raw;
        $array['_tax']              = $cartItem->_tax;

        $array['taxRates']          = $cartItem->taxRates;
        $array['taxes']             = [];
        foreach($cartItem->taxRates as $taxRate) {
            $array['taxes'][]       = array('rate' => $taxRate, 'value' => $cartItem->taxForRate((int)$taxRate), 'taxable' => $cartItem->taxableForRate((int)$taxRate));
        } 
        
        return $array;
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
        $cartItems = $cart->content();
        foreach($cartItems as $cartKey => $cartItem) {
            foreach($products as $product) {
                $json = $product->json;
                
                if(!$this->productRepository->hasSKU($product, $cartItem->id)) {
                    continue;
                }

                if(!$this->productRepository->isCombination($product, $cartItem->id)) {
                    //subtract quantity from stock
                    $json['quantity'] = $json['quantity'] - $cartItem->qty;
                    $product->json = $json;
                    $product->update();
                    break;
                }

                $combination = $this->productRepository->combinationForSKU($product, $cartItem->id);
                $combinationKey = $this->productRepository->combinationKeyForSKU($product, $cartItem->id);

                $json['combinations'][$combinationKey]['quantity'] = $combination['quantity'] - $cartItem->qty;

                $product->json = $json;
                if($product->update()) {
                    break;
                } else {
                    //account for products' stock that was already updated
                    return false;
                }
            }
        }

        return true;
    }

    public function addItemsToStock($products, $cart) 
{
        $cartItems = $cart->content();
        foreach($cartItems as $cartKey => $cartItem) {
            foreach($products as $product) {
                $json = $product->json;
                
                if(!$this->productRepository->hasSKU($product, $cartItem->id)) {
                    continue;
                }

                if(!$this->productRepository->isCombination($product, $cartItem->id)) {
                    //subtract quantity from stock
                    $json['quantity'] = $json['quantity'] + $cartItem->qty;
                    $product->json = $json;
                    $product->update();
                    break;
                }

                $combination = $this->productRepository->combinationForSKU($product, $sku);
                $combinationKey = $this->productRepository->combinationKeyForSKU($product, $sku);

                $json['combinations'][$combinationKey]['quantity'] = $combination['quantity'] + $cartItem->qty;
                $product->json = $json;
                $product->update();
                break;
            }
        }
    }

    public function clear($cart)
    {
        if(Auth::check()) {
            ChuckCart::instance('shopping')->restore('shopping_'.Auth::user()->id);
        }
        ChuckCart::instance('shopping')->destroy();
        $cart->destroy();

        if(Auth::check()) {
            ChuckCart::instance('shopping')->store('shopping_'.Auth::user()->id);
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

    /**
     * is a collection present in the cart
     *
     * @var ChuckCart $cart
     * @var string $collectionId
     *
     * @return bool
     **/
    public function isCollectionPresent($cart, string $collectionId)
    {
        foreach( $cart->content() as $cartItem ) {
            if($this->productRepository->hasCollectionBySKU($collectionId, $cartItem->id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * is a brand present in the cart
     *
     * @var ChuckCart $cart
     * @var string $brandId
     *
     * @return bool
     **/
    public function isBrandPresent($cart, string $brandId)
    {
        foreach( $cart->content() as $cartItem ) {
            if($this->productRepository->hasBrandBySKU($brandId, $cartItem->id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * is a product present in the cart
     *
     * @var ChuckCart $cart
     * @var string $productId
     *
     * @return bool
     **/
    public function isProductPresent($cart, string $productId)
    {
        foreach( $cart->content() as $cartItem ) {
            if($productId == $cartItem->id) {
                return true;
            }
        }

        return false;
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