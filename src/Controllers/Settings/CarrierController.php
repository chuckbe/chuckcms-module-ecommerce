<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Chuckbe\Chuckcms\Models\Module;
use Str;
use ChuckEcommerce;
use ChuckSite;

class CarrierController extends Controller
{
    private $module;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function save(Request $request)
    {
        $this->validate($request, [ 
            'name.*' => 'required',
            'transit_time.*' => 'required',
            'image' => 'nullable',
            'min_cart' => 'required',
            'cost' => 'required',
            'max_cart' => 'required',
            'min_weight' => 'required',
            'max_weight' => 'required',
            'free_from' => 'nullable',
            'countries' => 'array',
            'default' => 'required|in:true,false',
            'slug' => 'required_with:update'
        ]);
        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $json = $ecommerce->json;

        if($request->has('slug') && $request->has('update')) {
            $slug = $request->get('slug');
            $carrier = $json['settings']['shipping']['carriers'][$slug];
        } elseif($request->has('create')) {
            $slug = Str::slug($request->get('name'), '_');
            $carrier = [];
        }

        $langs = ChuckSite::getSupportedLocales();
        foreach ($langs as $langKey => $langValue) {
            $carrier['name'][$langKey] = $request->get('name')[$langKey];
            $carrier['transit_time'][$langKey] = $request->get('transit_time')[$langKey];
        }
        
        $carrier['image'] = $request->get('image');
        $carrier['min_cart'] = $request->get('min_cart');
        $carrier['cost'] = $request->get('cost');
        $carrier['max_cart'] = $request->get('max_cart');
        $carrier['min_weight'] = $request->get('min_weight');
        $carrier['max_weight'] = $request->get('max_weight');
        $carrier['free_from'] = (float)$request->get('free_from') == 0 ? null : (string)$request->get('free_from');
        $carrier['countries'] = $request->get('countries');
        $carrier['default'] = $request->get('default') == 'true' ? true : false;

        $json['settings']['shipping']['carriers'][$slug] = $carrier;
        $ecommerce->json = $json;

        if($ecommerce->save()){
            return redirect()->route('dashboard.module.ecommerce.settings.index.shipping')
                        ->with('notification', 'Instellingen opgeslagen!');
        } else {
            return redirect()->route('dashboard.module.ecommerce.settings.index.shipping')
                        ->with('notification', 'Er is iets misgegaan, probeer het later nog eens!');
        }
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['key' => 'required']);
        $carrierKey = $request->get('key');

        $ecommerce = $this->module->where('slug', 'chuckcms-module-ecommerce')->first();
        $json = $ecommerce->json;

        $carrier_object = $json['settings']['shipping']['carriers'];

        $object = [];

        foreach ($carrier_object as $key => $carrier) {
            if($key !== $carrierKey) {
                $object[$key] = $json['settings']['shipping']['carriers'][$key];
            }
        }

        $json['settings']['shipping']['carriers'] = $object;

        $ecommerce->json = $json;
        $ecommerce->update();

        return redirect()->route('dashboard.module.ecommerce.settings.index.shipping');    
    }
}
