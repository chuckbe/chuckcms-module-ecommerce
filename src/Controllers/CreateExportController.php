<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Chuckbe\Chuckcms\Models\Repeater;
use Maatwebsite\Excel\Facades\Excel;
use Chuckbe\ChuckcmsModuleEcommerce\Exports\FacebookExport;

use App\Http\Controllers\Controller;

class CreateExportController extends Controller
{

    public function __construct(Repeater $repeater)
    {

        $this->repeater = $repeater;
    }

    public function index()
    {
        return Excel::download(new FacebookExport, 'fbExport.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}