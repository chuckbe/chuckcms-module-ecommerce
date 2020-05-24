<?php 

namespace Chuckbe\ChuckcmsModuleEcommerce\Facades;

use Illuminate\Support\Facades\Facade;

class Ecommerce extends Facade {
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChuckEcommerce';
    }
}