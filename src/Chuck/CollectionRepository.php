<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;

class CollectionRepository
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
        return $this->repeater->where('slug', 'collections')->get();
    }

    /**
     * Create a new collection
     *
     * @var array $values
     **/
    public function create(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.collections.slug');
        $input['url'] = config('chuckcms-module-ecommerce.collections.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.collections.page');
        $input['json']['name'] = $values->name;
        $input['json']['parent'] = $values->parent;
        $input['json']['image'] = $values->image;

        $collection = $this->repeater->create($input);

        return $collection;
    }

    /**
     * Create a new collection
     *
     * @var array $values
     **/
    public function update(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.collections.slug');
        $input['url'] = config('chuckcms-module-ecommerce.collections.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.collections.page');
        
        $json = [];
        $json['name'] = $values->name;
        $json['image'] = $values->image;
        $json['parent'] = $values->parent;

        $collection = $this->repeater->where('id', $values->id)->first();
        $collection->slug = $input['slug'];
        $collection->url = $input['url'];
        $collection->page = $input['page'];
        $collection->json = $json;
        $collection->update();
        
        return $collection;
    }

    /**
     * Delete a collection
     *
     * @var int $id
     **/
    public function delete(int $id): bool
    {
        $this->repeater->destroy($id);
        return true;
    }

}