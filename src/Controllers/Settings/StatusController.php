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

class StatusController extends Controller
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

    public function edit($status)
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();
        $statusKey = $status;
        $status = ChuckEcommerce::getSetting('order.statuses.'.$status);

        return view('chuckcms-module-ecommerce::backend.settings.orders.edit_status', ['module' => $ecommerce, 'templates' => $templates, 'status' => $status, 'statusKey' => $statusKey]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'is_paid' => 'required|in:0,1',
            'is_delivered' => 'required|in:0,1',
            'has_invoice' => 'required|in:0,1',
            'display_name' => 'required|array',
            'short' => 'required|array',
            'to' => 'required|array',
            'to_name' => 'required|array',
            'cc' => 'nullable',
            'bcc' => 'nullable',
            'template' => 'required|array',
            'logo' => 'required|array',
            'send_delivery_note' => 'required|array',
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
            $json['settings']['order']['statuses'][$statusKey]['send_email'] = true;

            foreach( $request->get('email_key') as $emailKey) {
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to'] = $request->get('to')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to_name'] = $request->get('to_name')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['cc'] = $request->get('cc')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['bcc'] = $request->get('bcc')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['template'] = $request->get('template')[$emailKey];
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['logo'] = $request->get('logo')[$emailKey] == '1' ? true : false;
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['send_delivery_note'] = $request->get('send_delivery_note')[$emailKey] == '1' ? true : false;

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

    public function email($status)
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();
        $statusKey = $status;
        $status = ChuckEcommerce::getSetting('order.statuses.'.$status);

        return view('chuckcms-module-ecommerce::backend.settings.orders.new_status_email', ['module' => $ecommerce, 'templates' => $templates, 'status' => $status, 'statusKey' => $statusKey]);
    }

    public function emailSave(Request $request)
    {
        $emailKey = $request->get('email_key');
        $statusKey = $request->get('status_key');

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $json = $ecommerce->json;

        $email_key_strings = implode(',', array_keys($json['settings']['order']['statuses'][$statusKey]['email']));

        $this->validate($request, [
            'status_key' => 'required',
            'email_key' => 'required|not_in:'.$email_key_strings,
            'to' => 'required',
            'to_name' => 'required',
            'cc' => 'nullable',
            'bcc' => 'nullable',
            'template' => 'required',
            'logo' => 'required|in:0,1',
            'send_delivery_note' => 'required|in:0,1',
            'status_slug' => 'required|array',
            'status_required' => 'required|array',
            'status_textarea' => 'required|array',
        ]);

        $langs = ChuckSite::getSupportedLocales();

        $json['settings']['order']['statuses'][$statusKey]['send_email'] = true;
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to'] = $request->get('to');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to_name'] = $request->get('to_name');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['cc'] = $request->get('cc');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['bcc'] = $request->get('bcc');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['template'] = $request->get('template');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['logo'] = $request->get('logo') == '1' ? true : false;
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['send_delivery_note'] = $request->get('send_delivery_note') == '1' ? true : false;

        $loop = 0;
        foreach ($request->get('status_slug') as $slug) {
            $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['type'] = $request->get('status_textarea')[$loop] == '1' ? 'textarea' : 'text';
            foreach ($langs as $langKey => $langValue) {
                $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['value'][$langKey] = '';
            }
            $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['required'] = $request->get('status_required')[$loop] == '1' ? true : false;
            $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['validation'] = $request->get('status_required')[$loop] == '1' ? 'required' : 'nullable';
            $loop++;
        }

        $ecommerce->json = $json;
        $ecommerce->update();

        return redirect()->route('dashboard.module.ecommerce.settings.index.orders.statuses.edit', ['status' => $statusKey])->with('notification', 'Instellingen opgeslagen!');
    }

    public function emailDelete(Request $request)
    {
        $emailKey = $request->get('email_key');
        $statusKey = $request->get('status_key');

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $json = $ecommerce->json;

        $email_key_strings = implode(',', array_keys($json['settings']['order']['statuses'][$statusKey]['email']));

        $this->validate($request, [
            'status_key' => 'required',
            'email_key' => 'required|in:'.$email_key_strings,
        ]);

        $email_object = $json['settings']['order']['statuses'][$statusKey]['email'];

        $object = [];

        foreach ($email_object as $key => $email) {
            if($key !== $emailKey) {
                $object[$key] = $json['settings']['order']['statuses'][$statusKey]['email'][$key];
            }
        }

        $json['settings']['order']['statuses'][$statusKey]['email'] = $object;
        if(count($object) == 0) {
            $json['settings']['order']['statuses'][$statusKey]['send_email'] = false;
        }

        $ecommerce->json = $json;
        $ecommerce->update();

        return response()->json(['status' => 'success']);

    }
}
