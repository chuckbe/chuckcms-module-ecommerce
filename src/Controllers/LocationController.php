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
        
        return redirect()->route('dashboard.module.order_form.locations.index');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        $delete = $this->locationRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.order_form.locations.index');
        }
    }
}