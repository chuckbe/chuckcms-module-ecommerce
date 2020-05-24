<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Requests;

use ChuckEcommerce;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_surname' => 'required',
            'customer_name' => 'required',
            'customer_email' => 'email|required',
            'customer_tel' => 'nullable',

            'customer_street' => 'required',
            'customer_housenumber' => 'required|max:50',
            'customer_postalcode' => 'required|max:8',
            'customer_city' => 'required',
            'customer_country' => 'required|in:'.implode(',', ChuckEcommerce::getSetting('order.countries')),
            
            'customer_company_name' => 'nullable',
            'customer_company_vat' => 'nullable|required_with:customer_company_name',

            'customer_shipping_street' => 'required_if:customer_shipping_equal_to_billing,0',
            'customer_shipping_housenumber' => 'required_if:customer_shipping_equal_to_billing,0|max:50',
            'customer_shipping_postalcode' => 'required_if:customer_shipping_equal_to_billing,0|max:8',
            'customer_shipping_city' => 'required_if:customer_shipping_equal_to_billing,0',
            'customer_shipping_country' => 'nullable|required_if:customer_shipping_equal_to_billing,0|in:'.implode(',', ChuckEcommerce::getSetting('order.countries')),

            'customer_shipping_equal_to_billing' => 'required|in:0,1',

            'check_out_as_guest' => 'required|in:-1,0,1',

            'customer_password' => 'required_if:check_out_as_guest,0',
            'customer_password_repeat' => 'required_if:check_out_as_guest,0',

            'shipping_method' => 'required',
            'payment_method' => 'required',

            'legal_approval' => 'required',
            'promo_approval' => 'nullable',

        ];
    }
}
