<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Chuckbe\ChuckcmsModuleEcommerce\Requests\StoreProductRequest;
use Chuckbe\ChuckcmsModuleEcommerce\Requests\DeleteProductRequest;

use Chuckbe\ChuckcmsModuleEcommerce\Models\Product;
use Chuckbe\Chuckcms\Models\Repeater;

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

    public function edit(Repeater $product)
    {
        $collections = $this->collectionRepository->get();
        $brands = $this->brandRepository->get();
        $attributes = $this->attributeRepository->get();
        return view('chuckcms-module-ecommerce::backend.products.edit', compact('collections', 'brands', 'attributes', 'product'));
    }

    public function save(StoreProductRequest $request)
    {
        $product = $this->productRepository->save($request);

        return redirect()->route('dashboard.module.ecommerce.products.index');
    }

    public function update(StoreProductRequest $request)
    {
        $product = $this->productRepository->update($request);

        return redirect()->route('dashboard.module.ecommerce.products.index');
    }

    public function delete(DeleteProductRequest $request)
    {
        $product = Product::where('id', $request->get('product_id'))->first();

        if ($product->delete()) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function getCombination(Request $request)
    {
        $this->validate(request(), [ //@todo create custom Request class for product validation
            'product_id' => 'required',
            'attribute_keys' => 'required|array'
        ]);

        $product = $this->productRepository->get($request->get('product_id'));
        $attributes = $request->get('attribute_keys');

        $combination = $this->productRepository->getCombination($product, $attributes);

        if ( is_array($combination) && count($combination) > 0 ) {
            return response()->json(['status' => 'success', 'combination' => $combination]);
        }

        return response()->json(['status' => 'error']);
    }
    
}
