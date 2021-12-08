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
            'integrations.mollie.methods' => 'required|array',
            'integrations.banktransfer.active' => 'required|in:0,1',
            'integrations.banktransfer.name' => 'nullable',
            'integrations.banktransfer.iban' => 'nullable',
            'integrations.banktransfer.bank' => 'nullable'
        ]);

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        
        $json = $ecommerce->json;
        $json['settings']['integrations']['mollie']['key'] = $request->get('integrations')['mollie']['key'];
        $json['settings']['integrations']['mollie']['methods'] = $request->get('integrations')['mollie']['methods'];

        if (! array_key_exists('banktransfer', $json['settings']['integrations']) ) {
            $json['settings']['integrations']['banktransfer'] = [];
        }

        $json['settings']['integrations']['banktransfer']['active'] = $request->get('integrations')['banktransfer']['active'] == "1" ? true : false;
        $json['settings']['integrations']['banktransfer']['name'] = $request->get('integrations')['banktransfer']['name'];
        $json['settings']['integrations']['banktransfer']['iban'] = $request->get('integrations')['banktransfer']['iban'];
        $json['settings']['integrations']['banktransfer']['bank'] = $request->get('integrations')['banktransfer']['bank'];

        $ecommerce->json = $json;
        $ecommerce->update();

        return redirect()->route('dashboard.module.ecommerce.settings.index.integrations')->with('notification', 'Instellingen opgeslagen!');
    }

}