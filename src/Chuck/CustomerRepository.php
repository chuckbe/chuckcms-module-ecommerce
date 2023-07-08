<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\UserRepository;
use Illuminate\Http\Request;
use Auth;
use ChuckEcommerce;

class CustomerRepository
{
	private $customer;
    private $userRepository;

	public function __construct(Customer $customer, UserRepository $userRepository)
    {
        $this->customer = $customer;
        $this->userRepository = $userRepository;
    }

    /**
     * Get all the customers
     *
     * @var string
     **/
    public function get()
    {
        return $this->customer->get();
    }

    /**
     * Get the guest customer
     *
     * @var string
     **/
    public function guest()
    {
        return $this->customer->where('email', 'guest@guest.com')->first();
    }

    public function findByUserId($user_id)
    {
        $this->customer->where('user_id', $user_id)->first();
    }

    public function createFromUser($user, $request)
    {
        $json = [];

        $json = $this->mapAddress($request, $json);
        $json = $this->mapCompany($request, $json);
        $json['group'] = $this->defaultGroup();

        $customer = $user->customer()->create([
            'surname' => $request->get('customer_surname'),
            'name' => $request->get('customer_name'),
            'email' => $user->email,
            'tel' => $request->get('customer_tel'),
            'json' => $json
        ]);

        return $customer;
    }

    public function createFromRequest($request)
    {
        $json = [];

        $json = $this->mapAddress($request, $json);
        $json = $this->mapCompany($request, $json);
        $json['group'] = $this->guestGroup();

        $customer = Customer::create([
            'surname' => $request->get('customer_surname'),
            'name' => $request->get('customer_name'),
            'email' => $request->get('customer_email'),
            'tel' => $request->get('customer_tel'),
            'json' => $json
        ]);

        return $customer;
    }

    /**
     * Update a customer
     *
     * @var Request $request
     **/
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $customer->surname = $request->get('customer_surname');
        $customer->name = $request->get('customer_name');
        $customer->tel = $request->get('customer_tel');
        $customer->update();

        $this->userRepository->updateName($customer->surname . ' ' . $customer->name);

        return $customer;
    }

    /**
     * Update a customer
     *
     * @var Request $request
     **/
    public function updateAddress(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $json = $this->mapAddress($request, $customer->json);
        
        $customer->json = $json;
        $customer->update();

        return $customer;
    }

    /**
     * Update a customer
     *
     * @var Request $request
     **/
    public function updateCompany(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $json = $this->mapCompany($request, $customer->json);        

        $customer->json = $json;
        $customer->update();

        return $customer;
    }

    /**
     * Delete a customer
     *
     * @var int $id
     **/
    public function delete(int $id): bool
    {
        $this->customer->destroy($id);
        return true;
    }


    public function mapAddress(Request $request, $json)
    {
        $json['address']['billing']['street'] = $request->get('customer_street');
        $json['address']['billing']['housenumber'] = $request->get('customer_housenumber');
        $json['address']['billing']['postalcode'] = $request->get('customer_postalcode');
        $json['address']['billing']['city'] = $request->get('customer_city');
        $json['address']['billing']['country'] = $request->get('customer_country');

        $json['address']['shipping_equal_to_billing'] = $request->get('customer_shipping_equal_to_billing') == '1';
        if($request->get('customer_shipping_equal_to_billing') !== '1') {
            $json['address']['shipping']['street'] = $request->get('customer_shipping_street');
            $json['address']['shipping']['housenumber'] = $request->get('customer_shipping_housenumber');
            $json['address']['shipping']['postalcode'] = $request->get('customer_shipping_postalcode');
            $json['address']['shipping']['city'] = $request->get('customer_shipping_city');
            $json['address']['shipping']['country'] = $request->get('customer_shipping_country');
        }

        return $json;
    }

    public function mapCompany(Request $request, $json)
    {
        $json['company']['name'] = $request->get('customer_company_name');
        $json['company']['vat'] = $request->get('customer_company_vat');

        return $json;
    }

    public function defaultGroup()
    {
        $groups = ChuckEcommerce::getSetting('customer.groups');
        foreach($groups as $key => $group) {
            if($group['default']) {
                return $key;
            }
        }
    }

    public function allGroups()
    {
        return ChuckEcommerce::getSetting('customer.groups');
    }

    public function guestGroup()
    {
        $groups = ChuckEcommerce::getSetting('customer.groups');
        foreach($groups as $key => $group) {
            if($group['guest']) {
                return $key;
            }
        }
    }

}
