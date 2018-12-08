<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;

class BrandRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the brands
     *
     * @var 
     **/
    public function get()
    {
        return $this->repeater->where('slug', 'brands')->get();
    }

    /**
     * Create a new brand
     *
     * @var array $values
     **/
    public function create(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.brands.slug');
        $input['url'] = config('chuckcms-module-ecommerce.brands.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.brands.page');
        $input['json']['name'] = $values->name;
        $input['json']['logo'] = $values->logo;

        $brand = $this->repeater->create($input);

        return $brand;
    }

    /**
     * Create a new brand
     *
     * @var array $values
     **/
    public function update(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.brands.slug');
        $input['url'] = config('chuckcms-module-ecommerce.brands.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.brands.page');
        
        $json = [];
        $json['name'] = $values->name;
        $json['logo'] = $values->logo;

        $brand = $this->repeater->where('id', $values->id)->first();
        $brand->slug = $input['slug'];
        $brand->url = $input['url'];
        $brand->page = $input['page'];
        $brand->json = $json;
        $brand->update();
        
        return $brand;
    }

    /**
     * Delete a brand
     *
     * @var int $id
     **/
    public function delete(int $id): bool
    {
        $this->repeater->destroy($id);
        return true;
    }

    public function generateSingleSku()
    {

    }

}