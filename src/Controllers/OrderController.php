<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        return view('chuckcms-module-ecommerce::backend.orders.index');
    }
}
