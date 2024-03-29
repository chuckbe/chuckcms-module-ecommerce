<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Repeater;

class CollectionController extends Controller
{
    private $productRepository;
    private $collectionRepository;
    private $brandRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        CollectionRepository $collectionRepository,
        BrandRepository $brandRepository)
    {
        $this->productRepository = $productRepository;
        $this->collectionRepository = $collectionRepository;
        $this->brandRepository = $brandRepository;
    }

    public function index()
    {
        $products = $this->productRepository->get();
        $collections = $this->collectionRepository->get();
        $brands = $this->brandRepository->get();
        return view('chuckcms-module-ecommerce::backend.collections.index', compact('products', 'collections', 'brands'));
    }

    public function sorting(Repeater $collection)
    {
        $products = $this->productRepository->forCollection($collection->name, $collection->parent, false, true);
        return view('chuckcms-module-ecommerce::backend.collections.sorting', compact('collection', 'products'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'is_pos_available' => 'required|in:0,1',
            'parent' => 'nullable',
            'image' => 'nullable',
            'order' => 'required|numeric',
            'id' => 'required_with:update'
        ]);

        if ($request->has('id') && $request->has('update')) {
            $collection = $this->collectionRepository->update($request);
        } elseif ($request->has('create')) {
            $collection = $this->collectionRepository->create($request);
        }

        if ($collection->save()) {
            return redirect()->route('dashboard.module.ecommerce.collections.index');
        } else {
            return 'error';//add ThrowNewException
        }
    }

    public function updateSort(Request $request, Repeater $collection)
    {
        $this->validate($request, [ 
            'products' => 'required|array'
        ]);

        $this->productRepository->sortForCollection($collection, $request->products);

        return response()->json(['status' => 'success']);
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->collectionRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.ecommerce.collections.index');
        }
    }

    
}
