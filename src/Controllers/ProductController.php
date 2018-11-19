<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    private $productRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $products = $this->productRepository->get();
        return view('chuckcms-module-ecommerce::backend.products.index', compact('products'));
    }

    public function create()
    {
        return view('chuckcms-module-ecommerce::backend.products.create');
    }

    public function save(Request $request)
    {
        
    }

    
}
