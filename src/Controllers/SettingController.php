<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;
use Str;

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

        return view('chuckcms-module-ecommerce::backend.settings.index', ['module' => $ecommerce, 'templates' => $templates]);
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
