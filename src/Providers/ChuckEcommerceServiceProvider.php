<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Providers;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\Chuckcms\Models\Module;
use Exception;
use Illuminate\Support\ServiceProvider;

class ChuckEcommerceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ChuckEcommerce', function() {
            $module = Module::where('slug', 'chuckcms-module-ecommerce')->first();
            if ($module == null) {
                throw new Exception('Whoops! ChuckCMS Ecommerce Module Not Installed...');
            }
            return new \Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors\Ecommerce($module, \App::make(ModuleRepository::class), \App::make(OrderRepository::class), \App::make(CustomerRepository::class));
        });
    }
}