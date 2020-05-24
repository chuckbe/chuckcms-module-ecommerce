<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\Chuckcms\Models\Template;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AccountAddressController extends Controller
{
    private $attributeRepository;
    private $brandRepository;
    private $collectionRepository;
    private $customerRepository;
    private $productRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        BrandRepository $brandRepository,
        CollectionRepository $collectionRepository,
        CustomerRepository $customerRepository,
        ProductRepository $productRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->brandRepository = $brandRepository;
        $this->collectionRepository = $collectionRepository;
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $templateHintpath = config('chuckcms-module-ecommerce.auth.template.hintpath');
        $template = Template::where('type', 'ecommerce')->where('active', 1)->where('hintpath', $templateHintpath)->first();


        return view($templateHintpath.'::templates.'.$templateHintpath.'.account.address.index', compact('template'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [ 
            'customer_street' => 'max:185|required',
            'customer_housenumber' => 'max:60|required',
            'customer_postalcode' => 'max:8|required',
            'customer_city' => 'max:185|required',
            'customer_country' => 'max:3|required',
            'customer_company_name' => 'max:185|nullable',
            'customer_company_vat' => 'max:18|nullable',
            'customer_shipping_equal_to_billing' => 'required|in:0,1',
            'customer_shipping_street' => 'max:185|required_if:customer_shipping_equal_to_billing,0',
            'customer_shipping_housenumber' => 'max:60|required_if:customer_shipping_equal_to_billing,0',
            'customer_shipping_postalcode' => 'max:8|required_if:customer_shipping_equal_to_billing,0',
            'customer_shipping_city' => 'max:185|required_if:customer_shipping_equal_to_billing,0',
            'customer_shipping_country' => 'max:3|required_if:customer_shipping_equal_to_billing,0'

        ]);

        $customer = $this->customerRepository->updateAddress($request);
        $customer = $this->customerRepository->updateCompany($request);

        return redirect()->route('module.ecommerce.account.address.index')->with('notification', 'Adres gewijzigd!');
    }
    
}
