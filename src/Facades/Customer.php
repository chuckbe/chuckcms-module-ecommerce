<?php 

namespace Chuckbe\ChuckcmsModuleEcommerce\Facades;

use Illuminate\Support\Facades\Facade;

class Customer extends Facade {
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChuckCustomer';
    }
}