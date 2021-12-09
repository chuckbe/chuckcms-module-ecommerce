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

     /**
     * Get all the locations for user
     *
     * @var string
     **/
    public function getForUser($user_id)
    {
        $locations = $this->repeater->where('slug', config('chuckcms-module-ecommerce.locations.slug'))->get();
        $locIds = [];
        foreach($locations as $key => $location) {
            $pos_users = explode(',', $location->pos_users);
            if (in_array($user_id, $pos_users)) {
                $locIds[] = $location->id;
            }
        }
        return $this->repeater->where('slug', config('chuckcms-module-ecommerce.locations.slug'))->whereIn('id', $locIds)->get()->sortBy('json.order');
    }

    public function save(Request $values)
    {
        $input = [];

        $input['slug'] = config('chuckcms-module-ecommerce.locations.slug');
        $input['url'] = config('chuckcms-module-ecommerce.locations.url').str_slug($values->get('name'), '-');
        $input['page'] = config('chuckcms-module-ecommerce.locations.page');

        $json = [];
        $json['name'] = $values->get('name');

        $json['pos_users'] = is_null($values->get('pos_users')) ? '' : $values->get('pos_users');
        $json['pos_name'] = $values->get('pos_name');
        $json['pos_address1'] = $values->get('pos_address1');
        $json['pos_address2'] = is_null($values->get('pos_address2')) ? '' : $values->get('pos_address2');
        $json['pos_vat'] = $values->get('pos_vat');
        $json['pos_receipt_title'] = $values->get('pos_receipt_title');

        $json['pos_receipt_footer_line1'] = is_null($values->get('pos_receipt_footer_line1')) ? '' : $values->get('pos_receipt_footer_line1');
        $json['pos_receipt_footer_line2'] = is_null($values->get('pos_receipt_footer_line2')) ? '' : $values->get('pos_receipt_footer_line2');
        $json['pos_receipt_footer_line3'] = is_null($values->get('pos_receipt_footer_line3')) ? '' : $values->get('pos_receipt_footer_line3');
        $json['order'] = (int)$values->get('order');

        $input['json'] = $json;

        $of_location = $this->repeater->create($input);

        return $of_location;
    }

    public function delete(int $id)
    {
    	$of_location = $this->repeater->where('slug', config('chuckcms-module-ecommerce.locations.slug'))->where('id', $id)->first();
        if (is_null($of_location)) {
            return 'false';
        }
        
        if ($of_location->delete()) {
            return 'success';
        }

        return 'error';    
    }


}