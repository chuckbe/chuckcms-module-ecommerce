<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\LocationRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    private $locationRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function index()
    {
        $locations = $this->locationRepository->get();
        return view('chuckcms-module-ecommerce::backend.locations.index', compact('locations'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'pos_users' => 'nullable',
            'pos_name' => 'required',
            'pos_address1' => 'required',
            'pos_address2' => 'nullable',
            'pos_vat' => 'required',
            'pos_receipt_title' => 'required',
            'pos_receipt_footer_line1' => 'nullable',
            'pos_receipt_footer_line2' => 'nullable',
            'pos_receipt_footer_line3' => 'nullable',
            'order' => 'numeric|required',
            'id' => 'required_with:update'
        ]);

        if($request->has('create')) {
            $location = $this->locationRepository->save($request);
        }

        if($request->has('id') && $request->has('update')) {
            $location = $this->locationRepository->update($request);
        } 

        if(!$location->save()){
            
            return 'error';//add ThrowNewException
        }
        return redirect()->route('dashboard.module.ecommerce.locations.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->locationRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.ecommerce.locations.index');
        }
    }
}