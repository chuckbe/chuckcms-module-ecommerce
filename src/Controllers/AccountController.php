<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use ChuckEcommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class AccountController extends Controller
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
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        $template = ChuckEcommerce::getTemplate();

        return view($template->hintpath.'::templates.'.$template->slug.'.account.index', compact('template'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [ 
            'customer_surname' => 'max:185|required',
            'customer_name' => 'max:185|required',
            'customer_tel' => 'max:50|nullable'
        ]);

        $customer = $this->customerRepository->updateProfile($request);

        return redirect()->route('module.ecommerce.account.index')->with('notification', 'Profiel gewijzigd!');
    }
    
}
