<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    private $attributeRepository;
    private $brandRepository;
    private $collectionRepository;
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
        ProductRepository $productRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->brandRepository = $brandRepository;
        $this->collectionRepository = $collectionRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->get();
        return view('chuckcms-module-ecommerce::backend.products.index', compact('products'));
    }

    public function create()
    {
        $collections = $this->collectionRepository->get();
        $brands = $this->brandRepository->get();
        $attributes = $this->attributeRepository->get();
        return view('chuckcms-module-ecommerce::backend.products.create', compact('collections', 'brands', 'attributes'));
    }

    public function save(Request $request)
    {
        $this->validate(request(), [ //@todo create custom Request class for site validation
            'slug' => 'required'
        ]);

        $product = $this->productRepository->save($request);

        return redirect()->route('dashboard.module.ecommerce.products.index');
    }

    
}
