<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Cart;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Contracts\Buyable;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Support\Arr;

class CartItem implements Arrayable, Jsonable
{
    /**
     * The rowID of the cart item.
     *
     * @var string
     */
    public $rowId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $qty;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price without TAX of the cart item.
     *
     * @var float
     */
    public $price;

    /**
     * The options for this cart item.
     *
     * @var array
     */
    public $options;

    /**
     * The extra information for this cart item.
     *
     * @var array
     */
    public $extras;

    /**
     * The FQN of the associated model.
     *
     * @var string|null
     */
    private $associatedModel = null;

    /**
     * The tax rate for the cart item.
     *
     * @var int|float
     */
    private $taxRate = 0;

    /**
     * The tax rate for the cart item.
     *
     * @var boolean
     */
    private $taxed = false;

    /**
     * The discounts for the cart item.
     *
     * @var Collection
     */
    private $discounts;

    /**
     * Is item saved for later.
     *
     * @var boolean
     */
    private $isSaved = false;

    /**
     * CartItem constructor.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param array      $options
     * @param array      $extras
     * @param boolean    $taxed
     */
    public function __construct($id, $name, $price, array $options = [], array $extras = [], bool $taxed = false)
    {
        if(empty($id)) {
            throw new \InvalidArgumentException('Please supply a valid identifier.');
        }
        if(empty($name)) {
            throw new \InvalidArgumentException('Please supply a valid name.');
        }
        if(strlen($price) < 0 || ! is_numeric($price)) {
            throw new \InvalidArgumentException('Please supply a valid price.');
        }

        $this->id           = $id;
        $this->name         = $name;
        $this->price        = bcadd($price, '0', 2);
        $this->options      = new CartItemOptions($options);
        $this->extras       = new CartItemExtras($extras);
        $this->discounts    = new Collection;
        $this->rowId        = $this->generateRowId($id, $options, $extras);
        $this->taxed        = $taxed;
    }

    /**
     * Get an attribute from the cart item or get the associated model.
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if(property_exists($this, $attribute)) {
            return $this->{$attribute};
        }

        /* NEW ATTRIBUTES */
        if($attribute == '_unit_base') { //price of 1 unit (without extras)
            return $this->price;
        }

        if($attribute == '_unit_extras') {
            return $this->taxed ? $this->extras->total() : $this->extras->subtotal();
        }

        if($attribute == '_unit_raw') { //@TODO: retire
            return bcadd($this->_unit_base, $this->_unit_extras, 2);
        }

        if($attribute == '_unit') {
            return $this->_unit_raw;
        }

        if($attribute == '_total_base') {
            return bcmul($this->qty, $this->_unit_base, 2);
        }

        if($attribute == '_total_extras') {
            return bcmul($this->qty, $this->_unit_extras, 2);
        }

        if($attribute == '_total_raw') { //@TODO: retire
            return bcmul($this->qty, $this->_unit_raw, 2);
        }

        if($attribute == '_total') {
            return bcmul($this->qty, $this->_unit, 2);
        }

        if($attribute == '_discount_base') {
            return $this->calculateDiscount($this->_total_base);
        }

        if($attribute == '_discount_extras') {
            return $this->calculateDiscount($this->_total_extras);
        }

        if($attribute == '_discount_raw') {
            return $this->calculateDiscount($this->_total_raw);
        }

        if($attribute == '_discount') {
            return $this->calculateDiscount($this->_total);
        }

        if($attribute == '_final_base') {
            return round($this->_total_base - $this->_discount_base, 2);
        }

        if($attribute == '_final_extras_raw') {
            return ($this->_total_extras - $this->_discount_extras);
        }

        if($attribute == '_final_extras') {
            return round($this->_final_extras_raw, 2);
        }

        if($attribute == '_final_raw') {
            return ($this->_total_raw - $this->_discount_raw); //($this->_total_raw - $this->_discount_raw);
        }

        if($attribute == '_final') {
            return ($this->_total_raw - $this->_discount); //($this->_total_raw - $this->_discount_raw);
        }

        if($attribute == '_tax_base') {
            return round($this->_tax_base_raw, 2);
        }

        if($attribute == '_tax_base_raw') {
            $finalBaseRaw = ($this->_total_base - $this->_discount_base);
            return $this->calculateTax($finalBaseRaw, $this->taxRate);
        }

        if($attribute == '_tax_extras') {
            return round($this->_tax_extras_raw, 2);
        }

        if($attribute == '_tax_extras_raw') {
            $taxExtrasRaw = 0;
            foreach($this->extras as $extra) {
                if($this->taxed) {
                    $extraPrice = $this->calculateDiscount(($this->qty * (int)$extra['qty']) * floatval($extra['final']), true);
                } else {
                    $extraPrice = $this->calculateDiscount(($this->qty * (int)$extra['qty']) * floatval($extra['price']), true);
                }

                $taxExtrasRaw = $taxExtrasRaw + $this->calculateTax($extraPrice, (int)$extra['vat']['amount']);
            }

            if(count($this->taxRates) == 1) {
                $taxExtrasRaw = $this->calculateTax($this->_final_extras_raw, $this->taxRate);
            }
            
            return $taxExtrasRaw;
        }

        if($attribute == '_tax') {
            $_tax = 0;
            foreach($this->taxRates as $taxRate) {
                $_tax = $_tax + $this->taxForRate((int)$taxRate);
            }
            return $_tax;
        }

        if($attribute == 'taxRates') {
            $taxRates = $this->extras->taxRates();
            $taxRates[] = $this->taxRate;
            return array_unique($taxRates);
        }
        /* END OF NEW ATTRIBUTES */

        if ($attribute === 'subtotal') {
            return $this->_total;
        }

        if ($attribute === 'total') {
            return $this->_total;
        }

        if ($attribute === 'discount') {
            return $this->_discount;
        }

        if ($attribute === 'tax') {
            return $this->_tax;
        }

        if ($attribute === 'final') {
            return $this->_final;
        }

        if($attribute === 'model' && isset($this->associatedModel)) {
            return with(new $this->associatedModel)->find($this->id);
        }

        return null;
    }

    /**
     * Create a new instance from a Buyable.
     *
     * @param \Chuckbe\ChuckcmsModuleEcommerce\Cart\Contracts\Buyable $item
     * @param array                                      $options
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public static function fromBuyable(Buyable $item, array $options = [])
    {
        return new self($item->getBuyableIdentifier($options), $item->getBuyableDescription($options), $item->getBuyablePrice($options), $options);
    }

    /**
     * Create a new instance from the given array.
     *
     * @param array $attributes
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public static function fromArray(array $attributes)
    {
        $options = Arr::get($attributes, 'options', []);
        $extras = array_get($attributes, 'extras', []);

        return new self($attributes['id'], $attributes['name'], $attributes['price'], $options, $extras, $attributes['taxed']);
    }

    /**
     * Create a new instance from the given attributes.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param array      $options
     * @param array      $extras
     * @param bool       $taxed
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public static function fromAttributes($id, $name, $price, array $options = [], array $extras = [], bool $taxed)
    {
        return new self($id, $name, $price, $options, $extras, $taxed);
    }

    /**
     * Returns the tax for the given rate.
     *
     * @param int    $rate
     * @return float
     */
    public function taxForRate(int $rate)
    {
        $tax = 0;

        if(!in_array($rate, $this->taxRates)) {
            return $tax;
        }

        if($rate == $this->taxRate) {
            $tax = $tax + $this->calculateTax($this->_final_base, $rate);
        }

        foreach($this->extras as $extra) {
            if($rate !== $extra['vat']['amount']) {
                continue;
            }

            if(count($this->extras) == 1) {
                $tax = $tax + $this->calculateTax(($this->taxed ? $this->_final_extras : $this->_final_extras_raw), $rate);
                continue;
            }
            
            $totalExtrasQuantity = bcmul($this->qty, $extra['qty'], 2);

            if($this->taxed) {
                $extraPrice = $this->calculateDiscount(bcmul($totalExtrasQuantity, $extra['final'], 2), true);
            } else {
                $extraPrice = $this->calculateDiscount(bcmul($totalExtrasQuantity, $extra['price'], 2), true);
            }

            $tax = bcadd($tax, $this->calculateTax($extraPrice, $rate), 3);
        }

        if( count($this->taxRates) == 1 && head($this->taxRates) == $rate ){
            $tax = $this->calculateTax($this->_final, $rate);
        }

        return $tax;
    }

    /**
     * Returns the taxable amount for the given rate.
     *
     * @param int    $rate
     * @return float
     */
    public function taxableForRate(int $rate)
    {
        $taxable = 0;

        if(!in_array($rate, $this->taxRates)) {
            return $taxable;
        }

        if($rate == $this->taxRate) {
            $taxable = $taxable + $this->_final_base;
        }

        foreach($this->extras as $extra) {
            if($rate !== $extra['vat']['amount']) {
                continue;
            }

            if(count($this->extras) == 1) {
                $taxable = $taxable + ($this->taxed ? $this->_final_extras : $this->_final_extras_raw);
                continue;
            }            

            if($this->taxed) {
                $taxable = $taxable + ($this->qty * ($this->calculateDiscount($extra['qty'] * $extra['final'], true)));
            } else {
                $taxable = $taxable + ($this->qty * ($this->calculateDiscount($extra['qty'] * $extra['price'], true)));
            }
        }

        if( count($this->taxRates) == 1 && head($this->taxRates) == $rate ){
            if($this->taxed) {
                $taxable = $this->_final;
            } else {
                $taxable = $this->_final_raw;
            }
        }

        return $taxable;
    }






    /**
     * Returns the formatted price without TAX.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function price($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->price, $decimals, $decimalPoint, $thousandSeperator);
    }
    
    /**
     * Returns the formatted price with TAX.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function priceTax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->priceTax, $decimals, $decimalPoint, $thousandSeperator);
    }
    
    /**
     * Returns the formatted total.
     * Total is price for whole CartItem with TAX
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->total, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Returns the formatted tax.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function tax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->tax, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Update the cart item from a Buyable.
     *
     * @param \Chuckbe\ChuckcmsModuleEcommerce\Cart\Contracts\Buyable $item
     * @return void
     */
    public function updateFromBuyable(Buyable $item)
    {
        $this->id       = $item->getBuyableIdentifier($this->options); //update for extras
        $this->name     = $item->getBuyableDescription($this->options);//update for extras
        $this->price    = $item->getBuyablePrice($this->options);//update for extras
    }

    /**
     * Update the cart item from an array.
     *
     * @param array $attributes
     * @return void
     */
    public function updateFromArray(array $attributes)
    {
        $this->id       = Arr::get($attributes, 'id', $this->id);
        $this->qty      = Arr::get($attributes, 'qty', $this->qty);
        $this->name     = Arr::get($attributes, 'name', $this->name);
        $this->price    = Arr::get($attributes, 'price', $this->price);
        $this->options  = new CartItemOptions(Arr::get($attributes, 'options', $this->options));
        $this->extras  = new CartItemExtras(array_get($attributes, 'extras', $this->extras));

        $this->rowId = $this->generateRowId($this->id, $this->options->all(), $this->extras->all());
    }

    /**
     * Associate the cart item with the given model.
     *
     * @param mixed $model
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public function associate($model)
    {
        $this->associatedModel = is_string($model) ? $model : get_class($model);
        
        return $this;
    }

    /**
     * Set the quantity for this cart item.
     *
     * @param int|float $qty
     */
    public function setQuantity($qty)
    {
        if(empty($qty) || ! is_numeric($qty))
            throw new \InvalidArgumentException('Please supply a valid quantity.');

        $this->qty = $qty;
    }

    /**
     * Set the tax rate.
     *
     * @param int|float $taxRate
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
        
        return $this;
    }

    /**
     * Set saved state.
     *
     * @param bool $bool
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public function setSaved($bool)
    {
        $this->isSaved = $bool;

        return $this;
    }

    /**
     * Set the discount.
     *
     * @param array $attributes
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public function setDiscount(array $attributes)
    {
        if (!isset($attributes[0])) {
            throw new \InvalidArgumentException('Please supply valid discount attributes.');
        }

        $discounts = $this->discounts;

        $discounts->put($attributes[0], new CartItemDiscount($attributes[0], isset($attributes[1]) ? $attributes[1] : null, isset($attributes[2]) ? $attributes[2] : null, isset($attributes[3]) ? $attributes[3] : null));

        $this->discounts = $discounts;

        return $this;
    }

    /**
     * Remove the discount.
     *
     * @return \Chuckbe\ChuckcmsModuleEcommerce\Cart\CartItem
     */
    public function removeDiscount()
    {
        $this->discounts = new Collection;

        return $this;
    }

    /**
     * Generate a unique id for the cart item.
     *
     * @param string $id
     * @param array  $options
     * @param array  $extras
     * @return string
     */
    protected function generateRowId($id, array $options, array $extras)
    {
        ksort($options);

        return md5($id . serialize($options) . serialize($extras));
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'rowId'     => $this->rowId,
            'id'        => $this->id,
            'name'      => $this->name,
            'qty'       => $this->qty,
            'price'     => $this->price,
            'options'   => $this->options->toArray(),
            'extras'    => $this->extras->toArray(),
            'discounts' => $this->discounts->toArray(),
            'tax'       => $this->tax,
            'isSaved'   => $this->isSaved,
            'subtotal'  => $this->subtotal
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        if (isset($this->associatedModel)){
            
           return json_encode(array_merge($this->toArray(), ['model' => $this->model]), $options);
        }
        
        return json_encode($this->toArray(), $options);
    }

    /**
     * Return the discount
     *
     * @param  int|float    $price
     * @param  boolean      $applied
     * @return int|float
     */
    protected function calculateDiscount($price, $applied = false)
    {
        $value = 0;
        foreach($this->discounts as $discountCode => $discount) { //sort by priority
            //if conditions match
            $value = bcadd($value, $discount->calculateDiscount($price), 2);
            $price = $discount->applyDiscount($price);
        }

        return !$applied ? $value : $price;
    }

    /**
     * Return the discount
     *
     * @param  int|float    $price
     * @param  int          $rate
     * @param  boolean      $applied
     * @return int|float
     */
    protected function calculateTax($price, int $rate, $applied = false)
    {
        if($this->taxed) {
            $tax = bcmul(bcdiv($price, bcadd('100', $rate, 2), 6), $rate, 6);
        } else {
            $tax = bcmul($price, bcdiv($rate, '100'), 6);
        }

        $taxed = round(bcsub($price, $tax, 6), 6);
        $untaxed = round(bcadd($price, $tax, 6), 6);

        return !$applied ? 
                    $tax : ($this->taxed ? $taxed : $untaxed);
    }

    /**
     * Get the formatted number.
     *
     * @param float  $value
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    private function numberFormat($value, $decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        if (is_null($decimals)){
            $decimals = is_null(config('cart.format.decimals')) ? 2 : config('cart.format.decimals');
        }

        if (is_null($decimalPoint)){
            $decimalPoint = is_null(config('cart.format.decimal_point')) ? '.' : config('cart.format.decimal_point');
        }

        if (is_null($thousandSeperator)){
            $thousandSeperator = is_null(config('cart.format.thousand_seperator')) ? ',' : config('cart.format.thousand_seperator');
        }

        return number_format($value, $decimals, $decimalPoint, $thousandSeperator);
    }
}
