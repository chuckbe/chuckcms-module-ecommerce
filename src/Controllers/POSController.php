<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Chuckbe\Chuckcms\Models\Repeater;
use App\Http\Controllers\Controller;
use ChuckProduct;
use ChuckRepeater;

class POSController extends Controller
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }
    public function index()
    {
        return view('chuckcms-module-ecommerce::pos.index');
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