<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\ChuckcmsModuleEcommerce\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EcommerceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //@TODO: check auth / roles - correct way
    }

    public function dashboard()
    {
        $orders_count = Order::where('status', 'payment')->count();
        $orders = Order::where('status', 'payment')->limit(30)->get();

        return view('chuckcms-module-ecommerce::backend.dashboard.index', compact('orders', 'orders_count'));
    }
}
