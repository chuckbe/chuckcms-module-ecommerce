<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\LocationRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use Chuckbe\Chuckcms\Models\Repeater;
use App\Http\Controllers\Controller;
use ChuckProduct;
use ChuckRepeater;



class POSController extends Controller
{
    private $locationRepository;
	private $repeater;

	public function __construct(LocationRepository $locationRepository, Repeater $repeater)
    {
        $this->repeater = $repeater;
        $this->locationRepository = $locationRepository;
    }
    public function index()
    {
        $locations = $this->locationRepository->getForUser(\Auth::user()->id);
        return view('chuckcms-module-ecommerce::pos.index', compact('locations'));
    }

    public function convert()
    {                                                                                                                                                                                                                                       
        // dump(ChuckRepeater::for('products')->first()->id);
        // $json = ChuckRepeater::for('products')->first()->json;
        // $json['is_pos_available'] = false;
        // $p = $this->repeater->where('id', ChuckRepeater::for('products')->first()->id)->first();
        // // $p['json'] = $json;
        // // $p->save();
        // dump($p);
        foreach(ChuckRepeater::for('products') as $product){
            $json = $product->json;
            $json['is_pos_available'] = false;
            $p = $this->repeater->where('id', $product->id)->first();
            $p['json'] = $json;
            $p->save();
        }
    }
}