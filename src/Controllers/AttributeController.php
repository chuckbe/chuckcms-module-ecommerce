<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\BrandRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
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
        $attributes = $this->attributeRepository->get();
        return view('chuckcms-module-ecommerce::backend.attributes.index', compact('attributes'));
    }

    public function edit(int $id)
    {
        $attribute = $this->attributeRepository->getById($id);
        return view('chuckcms-module-ecommerce::backend.attributes.edit', compact('attribute'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'type' => 'required|in:select,radio,color',
            'id' => 'required_with:update'
        ]);
        if($request->has('id') && $request->has('update')) {
            $attribute = $this->attributeRepository->update($request);
        } elseif($request->has('create')) {
            $attribute = $this->attributeRepository->create($request);
        }

        if($attribute->save() && $request->has('id') && $request->has('update')){
            return redirect()->route('dashboard.module.ecommerce.attributes.index');
        } elseif($attribute->save()) {
            return redirect()->route('dashboard.module.ecommerce.attributes.edit', ['id' => $attribute->id]);
        } else {
            return 'error';//add ThrowNewException
        }
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->attributeRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.ecommerce.attributes.index');
        }
    }
    
}
