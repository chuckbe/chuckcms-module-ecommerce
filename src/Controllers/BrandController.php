<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BrandController extends Controller
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
        BrandRepository $brandRepository,
        CollectionRepository $collectionRepository,
        ProductRepository $productRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->collectionRepository = $collectionRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $brands = $this->brandRepository->get();
        return view('chuckcms-module-ecommerce::backend.brands.index', compact('brands'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'logo' => 'nullable',
            'id' => 'required_with:update'
        ]);
        if($request->has('id') && $request->has('update')) {
            $brand = $this->brandRepository->update($request);
        } elseif($request->has('create')) {
            $brand = $this->brandRepository->create($request);
        }

        if($brand->save()){
            return redirect()->route('dashboard.module.ecommerce.brands.index');
        } else {
            return 'error';//add ThrowNewException
        }
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->brandRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.ecommerce.brands.index');
        }
    }
    
}
