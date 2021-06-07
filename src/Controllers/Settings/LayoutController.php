<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;
use Str;
use ChuckEcommerce;
use ChuckSite;

class LayoutController extends Controller
{
    private $module;
    private $template;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Module $module, Template $template)
    {
        $this->module = $module;
        $this->template = $template;
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'layout.template' => 'required'
        ]);

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        
        $json = $ecommerce->json;
        $json['settings']['layout']['template'] = $request->get('layout')['template'];

        $ecommerce->json = $json;
        $ecommerce->update();

        return redirect()->route('dashboard.module.ecommerce.settings.index.layout')->with('notification', 'Instellingen opgeslagen!');
    }

}