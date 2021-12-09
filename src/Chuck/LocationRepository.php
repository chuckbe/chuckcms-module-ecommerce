<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;

class LocationRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the locations
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', config('chuckcms-module-ecommerce.locations.slug'))->get()->sortBy('json.order');
    }

}