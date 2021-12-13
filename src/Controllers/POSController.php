<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\LocationRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use Chuckbe\Chuckcms\Models\Repeater;
use App\Http\Controllers\Controller;
use ChuckProduct;
use ChuckRepeater;
use ChuckEcommerce;

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


    public function posHandler(Request $request)
    {
        if($request->query('variable') == 'get_product_combinations'){
            $product = ChuckRepeater::for('products')->where('id', $request->product_id)->first();
            $combinations = $product->json['combinations'];
            $view = view('chuckcms-module-ecommerce::pos.includes.combinations_modal', compact('combinations'))->render();;
            return response()->json(['status' => 'success', 'html' => $view]);
        };
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