<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CartRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Cart;
use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class DiscountRepository
{
    private $customerRepository;
    private $cartRepository;
    private $repeater;

	public function __construct(CustomerRepository $customerRepository, CartRepository $cartRepository, Repeater $repeater)
    {
        $this->customerRepository = $customerRepository;
        $this->cartRepository = $cartRepository;
        $this->repeater = $repeater;
    }

    /**
     * Get all the discounts
     *
     **/
    public function get()
    {
        return $this->query()->get();
    }

    /**
     * Find discount by code
     *
     **/
    public function code($code)
    {
        return $this->query()->where('json->code', $code)->first();
    }

    /**
     * Get the discount
     *
     * @var string
     **/
    public function getById($id)
    {
        if(!is_array($id)) {
            $id = [$id];
        }
        return $this->query()->whereIn('id', $id)->first();
    }

    /**
     * Create a new collection
     *
     * @var array $values
     **/
    public function create(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.discounts.slug');
        $input['url'] = config('chuckcms-module-ecommerce.discounts.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.discounts.page');
        
        $input['json']['name'] = $values->name;
        $input['json']['description'] = $values->description;
        $input['json']['code'] = $values->code;
        $input['json']['priority'] = $values->priority;
        $input['json']['highlight'] = $values->highlight == "1" ? true : false;
        $input['json']['active'] = $values->active == "1" ? true : false;

        $input['json']['valid_from'] = $values->valid_from;
        $input['json']['valid_until'] = $values->valid_until;
        $input['json']['minimum'] = $values->minimum;
        $input['json']['minimum_vat_included'] = $values->minimum_vat_included == "1" ? true : false;
        $input['json']['minimum_shipping_included'] = $values->minimum_vat_included == "1" ? true : false;
        $input['json']['available_total'] = $values->available_total;
        $input['json']['available_customer'] = $values->available_customer;
        $input['json']['customer_groups'] = $values->customer_groups;

        if(is_array($values->condition_type)) {
            foreach ($values->condition_type as $key => $condition_type) {
                $input['json']['conditions'][$key]['type'] = $condition_type;
                $input['json']['conditions'][$key]['value'] = $values->condition_value[$key];
            }
        } else {
            $input['json']['conditions'] = [];
        }

        $input['json']['type'] = $values->action_type;
        $input['json']['value'] = $values->action_value;

        $discount = $this->repeater->create($input);

        return $discount;
    }

    /**
     * Update an existing discount
     *
     * @var array $values
     **/
    public function update(Request $values)
    {
        $input = [];
        $input['slug'] = config('chuckcms-module-ecommerce.discounts.slug');
        $input['url'] = config('chuckcms-module-ecommerce.discounts.url').str_slug($values->name, '-');
        $input['page'] = config('chuckcms-module-ecommerce.discounts.page');
        
        $json = [];
        $json['name'] = $values->name;
        $json['description'] = $values->description;
        $json['code'] = $values->code;
        $json['priority'] = $values->priority;
        $json['highlight'] = $values->highlight == "1" ? true : false;
        $json['active'] = $values->active == "1" ? true : false;

        $json['valid_from'] = $values->valid_from;
        $json['valid_until'] = $values->valid_until;
        $json['minimum'] = $values->minimum;
        $json['minimum_vat_included'] = $values->minimum_vat_included == "1" ? true : false;
        $json['minimum_shipping_included'] = $values->minimum_vat_included == "1" ? true : false;
        $json['available_total'] = $values->available_total;
        $json['available_customer'] = $values->available_customer;
        $json['customer_groups'] = $values->customer_groups;

        if(is_array($values->condition_type)) {
            foreach ($values->condition_type as $key => $condition_type) {
                $json['conditions'][$key]['type'] = $condition_type;
                $json['conditions'][$key]['value'] = $values->condition_value[$key];
            }
        } else {
            $json['conditions'] = [];
        }

        $json['type'] = $values->action_type;
        $json['value'] = $values->action_value;

        $discount = $this->repeater->where('id', $values->id)->first();
        $discount->slug = $input['slug'];
        $discount->url = $input['url'];
        $discount->page = $input['page'];
        $discount->json = $json;
        $discount->update();
        
        return $discount;
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

    /**
     * Get the query
     *
     **/
    private function query()
    {
        return $this->repeater->where('slug', config('chuckcms-module-ecommerce.discounts.slug'));
    }

    public function checkValidity(Repeater $discount)
    {
        if(strtotime(now()) < strtotime($discount->valid_from)) {
            return false;
        }

        if(strtotime(now()) > strtotime($discount->valid_until)) {
            return false;
        }

        return true;
    }

    public function checkMinima(Repeater $discount, Cart $cart)
    {
        $cartValue = $cart->isTaxed ? ($cart->total() - $cart->tax()) : $cart->total();
        
        if($discount->minimum_vat_included) {
            $cartValue = $cart->isTaxed ? $cart->total() : ($cart->total() + $cart->tax());
        }

        if((float)$discount->minimum > (float)$cartValue) { 
            return false;
        }

        return true;
    }

    public function checkAvailability(Repeater $discount)
    {
        if($discount->available_total < 1) {
            return false;
        }

        return true;
    }

    public function checkAvailabilityForCustomer(Repeater $discount, $user_id)
    {
        if($discount->available_customer < 1) {
            return false;
        }

        if($discount->available_customer <= $this->timesUsedByUser($discount, $user_id)) {
            return false;
        }

        return true;
    }

    /**
     * Add usage tracking for discounts by customer
     *
     * @param  $discounts
     * @param  $customer
     * @return void
     **/
    public function addUsageByCustomer(array $discounts, $customer)
    {
        if(is_null($customer->user_id)) {
            return;
        }

        foreach($discounts as $discountKey => $cartDiscount) {
            $discount = $this->code($discountKey);
            $json = $discount->json;
            $json['available_total'] = (int)$json['available_total'] - 1;
            if(!array_key_exists('usage', $json)) {
                $json['usage'] = [];
            }
            $json['usage'][] = $customer->user_id;
            $discount->json = $json;
            $discount->update();
        }
    }

    private function timesUsedByUser(Repeater $discount, $user_id)
    {
        if(is_null($discount->usage)) {
            return 0;
        }

        if(in_array($user_id, $discount->usage)) {
            return array_count_values($discount->usage)[$user_id];
        }

        return 0;
    }

    public function checkAvailabilityForCustomerGroup(Repeater $discount, $user)
    {
        if(!is_null($user)) {
            $customer = $this->customerRepository->findByUserId($user->id);
            if(!is_null($customer) && in_array($customer->json['group'], $discount->customer_groups)) {
                return true;
            }
        }

        if(in_array($this->customerRepository->guestGroup(), $discount->customer_groups)) {
            return true;
        }

        return false;
    }

    public function checkConditions(Repeater $discount, Cart $cart)
    {
        if(is_null($discount->conditions)) {
            return true;
        }

        foreach($discount->conditions as $condition) {
            if(!$this->checkCondition($condition, $cart)) {
                return false;
            }
        }

        return true;
    }

    private function checkCondition($condition = [], Cart $cart)
    {
        if(!array_key_exists('type', $condition)) {
            return false;
        }

        if(!array_key_exists('value', $condition)) {
            return false;
        }

        switch ($condition['type']) {
            case 'collection':
                return $this->cartRepository->isCollectionPresent($cart, $condition['value']);
            case 'brand':
                return $this->cartRepository->isBrandPresent($cart, $condition['value']);
            case 'product':
                return $this->cartRepository->isProductPresent($cart, $condition['value']);
        }

        return false;
    }

}