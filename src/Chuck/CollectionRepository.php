<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the collections
     *
     **/
    public function get()
    {
        return $this->repeater->where('slug', 'collections')->get();
    }

    /**
     * Get all the parent collections
     *
     **/
    public function parents()
    {
        return $this->repeater->where('slug', 'collections')->whereJsonContains('json->parent', null)->get();
    }

    /**
     * Get all the children of a collection
     *
     * @var string
     **/
    public function children(Repeater $collection)
    {
        
        return $this->repeater->where('slug', 'collections')->whereJsonContains('json->parent', ''.$collection->id.'')->get();
    }

    /**
     * Does the collection have children
     *
     * @var string
     **/
    public function hasChildren(Repeater $collection)
    {
        return $this->repeater->where('slug', 'collections')->whereJsonContains('json->parent', ''.$collection->id.'')->count() > 0;
    }

    /**
     * Get the collection
     *
     * @var string
     **/
    public function getById($id)
    {
        if(!is_array($id)) {
            $id = [$id];
        }
        return $this->repeater->where('slug', 'collections')->whereIn('id', $id)->first();
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
        $input['url'] = config('chuckcms-module-ecommerce.collections.url').Str::slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.collections.page');
        $input['json']['name'] = $values->name;
        $input['json']['parent'] = $values->parent;
        $input['json']['image'] = $values->image;
        $input['json']['fb_product_category'] = $values->fb_product_category;
        $input['json']['google_product_category'] = $values->google_product_category;

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