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

class IntegrationsController extends Controller
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
            'integrations.mollie.key' => 'required',
            'integrations.mollie.methods' => 'required|array'
        ]);

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        
        $json = $ecommerce->json;
        $json['settings']['integrations']['mollie']['key'] = $request->get('integrations')['mollie']['key'];
        $json['settings']['integrations']['mollie']['methods'] = $request->get('integrations')['mollie']['methods'];

        $ecommerce->json = $json;
        $ecommerce->update();

        return redirect()->route('dashboard.module.ecommerce.settings.index.integrations')->with('notification', 'Instellingen opgeslagen!');
    }

}