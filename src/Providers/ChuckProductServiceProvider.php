<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Providers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\ProductRepository;
use Illuminate\Support\ServiceProvider;

class ChuckProductServiceProvider extends ServiceProvider
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
        $this->app->bind('ChuckProduct', function() {
            return new \Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors\Product(\App::make(ProductRepository::class));
        });
    }
}