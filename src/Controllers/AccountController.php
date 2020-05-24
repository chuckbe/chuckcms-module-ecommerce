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
        $templateHintpath = config('chuckcms-module-ecommerce.auth.template.hintpath');
        $template = Template::where('type', 'ecommerce')->where('active', 1)->where('hintpath', $templateHintpath)->first();
        //@TODO: use ChuckEcomm facade instead

        return view($templateHintpath.'::templates.'.$templateHintpath.'.account.index', compact('template'));
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
