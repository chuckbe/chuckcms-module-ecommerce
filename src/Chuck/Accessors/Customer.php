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

    public function address($billing = true) :array
    {
        $empty = array('street' => null, 'housenumber' => null, 'postalcode' => null, 'city' => null, 'country' => null);
        if(!array_key_exists('address', $this->currentCustomer->json)) {
            return $empty;
        }

        if ($billing) {
            if (!array_key_exists('billing', $this->currentCustomer->json['address'])) {
                return $empty;
            }
            return $this->currentCustomer->json['address']['billing'];
        } else {
            if (!array_key_exists('shipping', $this->currentCustomer->json['address'])) {
                return $empty;
            }
            if ($this->currentCustomer->json['address']['shipping_equal_to_billing']) {
                return $this->currentCustomer->json['address']['billing'];
            }
            return $this->currentCustomer->json['address']['shipping'];
        }

        return $empty;
    }

    public function company() :array
    {
        $empty = array('name' => null, 'vat' => null);
        if(!array_key_exists('company', $this->currentCustomer->json)) {
            return $empty;
        }

        return $this->currentCustomer->json['company'];
    }

    public function isShippingEqualToBilling() :bool
    {
        if (!array_key_exists('address', $this->currentCustomer->json)) {
            return true;
        }

        if (!array_key_exists('shipping', $this->currentCustomer->json['address'])) {
            return true;
        } 

        if (!array_key_exists('shipping_equal_to_billing', $this->currentCustomer->json['address'])) {
            return true;
        } 

        return (bool)$this->currentCustomer->json['address']['shipping_equal_to_billing'];
    }
}