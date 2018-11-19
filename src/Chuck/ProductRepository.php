<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;

class ProductRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the products
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', 'product')->get();
    }

    public function save($values)
    {

    }

}