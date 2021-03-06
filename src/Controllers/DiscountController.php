<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
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
        return view('chuckcms-module-ecommerce::backend.discounts.index');
    }

    public function create()
    {
        return view('chuckcms-module-ecommerce::backend.discounts.create');
    }
}
