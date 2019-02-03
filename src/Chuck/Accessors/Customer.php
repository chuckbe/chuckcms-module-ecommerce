<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer as CustomerModel;
use Exception;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests;

class Customer
{
    private $customerRepository;
    private $currentCustomer;

    public function __construct(CustomerModel $customer, CustomerRepository $customerRepository) 
    {
        $this->currentCustomer = $this->getCurrentCustomer($customer);
        $this->customerRepository = $customerRepository;
    }

    public static function forCustomer($customer)
    {
        return new static($customer, \App::make(CustomerRepository::class));
    }

    private function getCurrentCustomer(CustomerModel $customer)
    {
        return $customer;
    }

    public function get()
    {
        return $this->currentCustomer;
    }

}