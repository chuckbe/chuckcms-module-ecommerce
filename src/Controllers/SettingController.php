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
}
