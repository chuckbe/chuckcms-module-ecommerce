<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;
use Str;
use ChuckEcommerce;
use ChuckSite;

class SettingController extends Controller
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

    public function index()
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();

        return view('chuckcms-module-ecommerce::backend.settings.index._tab_general', ['module' => $ecommerce, 'templates' => $templates, 'tab' => 'general']);
    }

    public function layout()
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();

        return view('chuckcms-module-ecommerce::backend.settings.index._tab_layout', ['module' => $ecommerce, 'templates' => $templates, 'tab' => 'layout']);
    }

    public function orders()
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();

        return view('chuckcms-module-ecommerce::backend.settings.index._tab_orders', ['module' => $ecommerce, 'templates' => $templates, 'tab' => 'orders']);
    }

    public function statusEdit($status)
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();
        $statusKey = $status;
        $status = ChuckEcommerce::getSetting('order.statuses.'.$status);

        return view('chuckcms-module-ecommerce::backend.settings.orders.edit_status', ['module' => $ecommerce, 'templates' => $templates, 'status' => $status, 'statusKey' => $statusKey]);
    }

    public function statusEmail($status)
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();
        $statusKey = $status;
        $status = ChuckEcommerce::getSetting('order.statuses.'.$status);

        return view('chuckcms-module-ecommerce::backend.settings.orders.new_status_email', ['module' => $ecommerce, 'templates' => $templates, 'status' => $status, 'statusKey' => $statusKey]);
    }

    public function statusEmailSave(Request $request)
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
            'status_slug' => 'required|array',
            'status_required' => 'required|array',
            'status_textarea' => 'required|array',
        ]);

        $langs = ChuckSite::getSupportedLocales();

        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to'] = $request->get('to');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['to_name'] = $request->get('to_name');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['cc'] = $request->get('cc');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['bcc'] = $request->get('bcc');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['template'] = $request->get('template');
        $json['settings']['order']['statuses'][$statusKey]['email'][$emailKey]['logo'] = $request->get('logo') == '1' ? true : false;

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

        return redirect()->route('dashboard.module.ecommerce.settings.index.orders.statuses.edit', ['status' => $statusKey]);
    }

    public function statusEmailDelete(Request $request)
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

        $ecommerce->json = $json;
        $ecommerce->update();

        return response()->json(['status' => 'success']);

    }

    public function statusUpdate(Request $request)
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

        return redirect()->route('dashboard.module.ecommerce.settings.index.orders');
    }

    public function shipping()
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();

        return view('chuckcms-module-ecommerce::backend.settings.index._tab_shipping', ['module' => $ecommerce, 'templates' => $templates, 'tab' => 'shipping']);
    }

    public function integrations()
    {
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $templates = $this->template->where('active', 1)->where('type', 'ecommerce')->get();

        return view('chuckcms-module-ecommerce::backend.settings.index._tab_integrations', ['module' => $ecommerce, 'templates' => $templates, 'tab' => 'integrations']);
    }

    public function shippingCarrierSave(Request $request)
    {
        $this->validate($request, [ 
            'name' => 'max:185|required',
            'transit_time' => 'required',
            'image' => 'nullable',
            'cost' => 'required',
            'countries' => 'array',
            'default' => 'required|in:true,false',
            'slug' => 'required_with:update'
        ]);
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();

        if($request->has('slug') && $request->has('update')) {
            //$collection = $this->collectionRepository->update($request);
            //@TODO: add create carrier functionality - easy to combine just
        } elseif($request->has('create')) {
            $carrier = [];
            $carrier['name'] = $request->get('name');
            $carrier['transit_time'] = $request->get('transit_time');
            $carrier['image'] = $request->get('image');
            $carrier['cost'] = $request->get('cost');
            $carrier['countries'] = $request->get('countries');
            $carrier['default'] = $request->get('default') == 'true' ? true : false;

            $slug = Str::slug($request->get('name'), '_');

            $json = $ecommerce->json;
            $json['settings']['shipping']['carriers'][$slug] = $carrier;
            $ecommerce->json = $json;
        }

        if($ecommerce->save()){
            return redirect()->route('dashboard.module.ecommerce.settings.index')->with('notification', 'Instellingen opgeslagen!');
        } else {
            return redirect()->route('dashboard.module.ecommerce.settings.index')->with('notification', 'Er is iets misgegaan, probeer het later nog eens!');
        }
    }

    public function shippingCarrierDelete(Request $request)
    {
        $this->validate($request, ['id' => 'required']);

        //$delete = $this->collectionRepository->delete($request->get('id'));

        if($delete){
            return redirect()->route('dashboard.module.ecommerce.settings.index');
        }
    }
}
