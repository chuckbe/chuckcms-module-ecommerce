<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Cart;

use Illuminate\Support\Collection;

class CartItemDiscount extends Collection
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int|float
     */
    private $value;

    /**
     * @var string
     */
    private $description;

    /**
     * CartItemDiscount constructor.
     * @param int|float $value
     * @param string $type
     * @param string $description
     */
    public function __construct($code, $type = 'currency', $value, $description = '')
    {
        if ($type == 'percentage' && ($value < 0 || $value > 100)) {
            throw new \InvalidArgumentException('Please supply a valid discount value.');
        }

        if ($type != 'currency' && $type != 'percentage') {
            throw new \InvalidArgumentException('Please supply a valid discount type.');
        }

        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
        $this->description = $description;
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

        if($attribute === 'symbol') {
            switch ($this->type) {
                case 'currency':
                    return '-';
                    break;
                case 'percentage':
                    return '%';
                    break;
            }
        }

        return null;
    }

    public function format($moneySymbol = '', $decimals = null, $decimalPoint = null, $thousandSeparator = null)
    {
        switch ($this->type) {
            case 'currency':
                return '- ' . $moneySymbol . $this->numberFormat($this->value, $decimals, $decimalPoint, $thousandSeparator);
                break;
            case 'percentage':
                return '- ' . $this->numberFormat($this->value, $decimals, $decimalPoint, $thousandSeparator) . '%';
                break;
        }
    }

    public function applyDiscount($price)
    {
        return $price - $this->calculateDiscount($price);
    }

    public function calculateDiscount($price)
    {
        $price = floatval($price);
        switch ($this->type) {
            case 'currency':
                return ($this->value > $price) ? $price : $this->value;
                break;
            case 'percentage':
                return round(($price * ($this->value / 100)), 6);
                break;
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'code'          => $this->code,
            'type'          => $this->type,
            'value'         => $this->value,
            'description'   => $this->description
        ];
    }

    /**
     * Get the Formated number
     *
     * @param $value
     * @param $decimals
     * @param $decimalPoint
     * @param $thousandSeparator
     * @return string
     */
    private function numberFormat($value, $decimals, $decimalPoint, $thousandSeparator)
    {
        if(is_null($decimals)){
            $decimals = is_null(config('cart.format.decimals')) ? 2 : config('cart.format.decimals');
        }
        if(is_null($decimalPoint)){
            $decimalPoint = is_null(config('cart.format.decimal_point')) ? '.' : config('cart.format.decimal_point');
        }
        if(is_null($thousandSeparator)){
            $thousandSeparator = is_null(config('cart.format.thousand_seperator')) ? ',' : config('cart.format.thousand_seperator');
        }

        return number_format($value, $decimals, $decimalPoint, $thousandSeparator);
    }
}
