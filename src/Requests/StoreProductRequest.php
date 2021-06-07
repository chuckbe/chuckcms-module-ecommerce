<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Requests;

use ChuckEcommerce;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'slug' => 'required',
            'code.upc' => 'nullable',
            'code.ean' => 'nullable',
            'is_displayed' => 'required|in:0,1',
            'is_buyable' => 'required|in:0,1',
            'is_download' => 'required|in:0,1',

            'price.wholesale' => 'nullable',
            'price.sale' => 'required',
            'price.vat' => 'required',
            'price.final' => 'required',
            'price.unit.amount' => 'nullable',
            'price.unit.type' => 'nullable',
            'price.discount' => 'nullable',

            'collection' => 'array|required',
            'brand' => 'required',

            'featured_image' => 'required',
            'image' => 'nullable|array',

            'attributes' => 'nullable|array',
            'combinations' => 'nullable|array',

            'option_key' => 'nullable|array',
            'option_value' => 'nullable|array',

            'extra_name' => 'nullable|array',
            'extra_price' => 'nullable|array',
            'extra_maximum' => 'nullable|array',

            'title' => 'required|array',
            'description.short' => 'required|array',
            'description.long' => 'required|array',
            'meta_title' => 'required|array',
            'meta_description' => 'required|array',
            'meta_keywords' => 'required|array',

            'width' => 'nullable',
            'height' => 'nullable',
            'depth' => 'nullable',
            'weight' => 'nullable',
        ];
    }
}
