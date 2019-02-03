<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;
use Illuminate\Http\Request;

class CustomerRepository
{
	private $customer;

	public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get all the customers
     *
     * @var string
     **/
    public function get()
    {
        return $this->customer->get();
    }

    /**
     * Delete a customer
     *
     * @var int $id
     **/
    public function delete(int $id): bool
    {
        $this->customer->destroy($id);
        return true;
    }

}