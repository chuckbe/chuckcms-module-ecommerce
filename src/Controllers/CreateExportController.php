<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Support\Facades\File; 
use Maatwebsite\Excel\Facades\Excel;
use ChuckProduct;
use ChuckRepeater;
use Chuckbe\ChuckcmsModuleEcommerce\Exports\FacebookExport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class CreateExportController extends Controller
{

    private $repeater;

    public function __construct(Repeater $repeater)
    {

        $this->repeater = $repeater;
    }

    public function index()
    {
        return Excel::download(new FacebookExport, 'fbExport.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}