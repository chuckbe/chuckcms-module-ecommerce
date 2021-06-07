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

class GeneralController extends Controller
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
            // 'general.supported_currencies' => 'required|array',
            // 'general.featured_currencies' => 'required',
 
            'is_paid' => 'required|in:0,1',
            'is_delivered' => 'required|in:0,1',
            'has_invoice' => 'required|in:0,1',
            'display_name' => 'required|array',
            'to' => 'required|array',
            'to_name' => 'required|array',
            'cc' => 'nullable',
            'bcc' => 'nullable',
            'template' => 'required|array',
            'logo' => 'required|array',
            'email.*' => 'required|array',
            'email_key' => 'required|array',
            'status_key' => 'required',
            '_has_email' => 'required|in:0,1'
        ]);

        $statusKey = $request->get('status_key');

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $json = $ecommerce->json;

        $langs = ChuckSite::getSupportedLocales();
        foreach ($langs as $langKey => $langValue) {
            $json['settings']['order']['statuses'][$statusKey]['display_name'][$langKey] = $request->get('display_name')[$langKey];
            $json['settings']['order']['statuses'][$statusKey]['short'][$langKey] = $request->get('short')[$langKey];
        }

        if($request->get('_has_email') == '1') {

            foreach( $request->get('email_key') as $emailKey) {
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to'] = $request->get('to')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to_name'] = $request->get('to_name')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['cc'] = $request->get('cc')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['bcc'] = $request->get('bcc')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['template'] = $request->get('template')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['logo'] = $request->get('logo')[$emailKey] == '1' ? true : false;

                foreach ($json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['data'] as $fieldKey => $field) {
                    foreach ($langs as $langKey => $langValue) {
                        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['data'][$fieldKey]['value'][$langKey] = $request->get('email')[$emailKey]['data'][$fieldKey][$langKey];
                    }
                }
            }

        }

        $json['settings']['order']['statuses'][$statusKey]['invoice'] = $request->get('has_invoice') == '1' ? true : false;
        $json['settings']['order']['statuses'][$statusKey]['delivery'] = $request->get('is_delivered') == '1' ? true : false;
        $json['settings']['order']['statuses'][$statusKey]['paid'] = $request->get('is_paid') == '1' ? true : false;

        $ecommerce->json = $json;
        $ecommerce->update();

        return redirect()->route('dashboard.module.ecommerce.settings.index.orders')->with('notification', 'Instellingen opgeslagen!');
    }

}
