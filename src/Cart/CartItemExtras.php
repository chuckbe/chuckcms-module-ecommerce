<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Cart;

use Illuminate\Support\Collection;

class CartItemExtras extends Collection
{
    /**
     * Get the option by the given key.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Get the total value of the extras including tax.
     *
     * @return float
     */
    public function total()
    {
        $total = 0;
        foreach ($this as $extraKey => $extra) {
            $total = $total + ((int)$extra['qty'] * ($extra['final'] + 0));
        }

        return $total;
    }

    /**
     * Get the subtotal value of the extras excluding tax.
     *
     * @param string $key
     * @return mixed
     */
    public function subtotal()
    {
        $total = 0;
        foreach ($this as $extraKey => $extra) {
            $total = $total + ((int)$extra['qty'] * ($extra['price'] + 0));
        }

        return $total;
    }

    /**
     * Get an unique array of the tax rates
     *
     * @param string $key
     * @return array
     */
    public function taxRates()
    {
        $taxRates = [];
        foreach ($this as $extraKey => $extra) {
            $taxRates[] = (int)$extra['vat']['amount'];
        }

        return array_unique($taxRates);
    }
} 