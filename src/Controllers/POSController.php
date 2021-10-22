<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class POSController extends Controller
{
    public function index()
    {
        return view('chuckcms-module-ecommerce::pos.index');
    }
}