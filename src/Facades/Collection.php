<?php 

namespace Chuckbe\ChuckcmsModuleEcommerce\Facades;

use Illuminate\Support\Facades\Facade;

class Collection extends Facade {
    /**
     * Return facade accessor
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChuckCollection';
    }
}