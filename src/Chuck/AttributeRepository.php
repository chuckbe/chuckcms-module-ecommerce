<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;
use ChuckSite;
use Illuminate\Http\Request;

class AttributeRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the attributes
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', config('chuckcms-module-ecommerce.attributes.slug'))->get();
    }

    /**
     * Get an attributes by id
     *
     * @var int $id
     **/
    public function getById($id)
    {
        return $this->repeater->where('slug', config('chuckcms-module-ecommerce.attributes.slug'))->where('id', $id)->firstOrFail();
    }

    /**
     * Create a new attribute
     *
     * @var array $values
     **/
    public function create(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.attributes.slug');
        $input['url'] = config('chuckcms-module-ecommerce.attributes.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.attributes.page');
        
        $input['json']['name'] = $values->name;
        $input['json']['type'] = $values->type;

        $attribute = $this->repeater->create($input);

        return $attribute;
    }

    /**
     * Update an attribute
     *
     * @var array $values
     **/
    public function update(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.attributes.slug');
        $input['url'] = config('chuckcms-module-ecommerce.attributes.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.attributes.page');
        
        $json = [];
        $json['name'] = $values->name;
        $json['type'] = $values->type;
        foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['display_name'][$langKey] = $values->get('display_name')[$langKey];
            for ($i=0; $i < count($values->get('attribute_key')[$langKey]); $i++) { 
                $json['values'][$values->get('attribute_key')[$langKey][$i]]['display_name'][$langKey] = $values->get('attribute_display_name')[$langKey][$i];
                $json['values'][$values->get('attribute_key')[$langKey][$i]]['value'] = $values->get('attribute_value')[$langKey][$i];
                $json['values'][$values->get('attribute_key')[$langKey][$i]]['value'] = $values->get('attribute_value')[$langKey][$i];
            }
        }

        $attribute = $this->repeater->where('id', $values->id)->first();
        $attribute->slug = $input['slug'];
        $attribute->url = $input['url'];
        $attribute->page = $input['page'];
        $attribute->json = $json;
        $attribute->update();
        
        return $attribute;
    }

    /**
     * Delete an attribute
     *
     * @var int $id
     **/
    public function delete(int $id): bool
    {
        $this->repeater->destroy($id);
        return true;
    }

}