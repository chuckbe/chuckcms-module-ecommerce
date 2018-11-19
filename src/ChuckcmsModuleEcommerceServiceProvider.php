<?php

namespace Chuckbe\ChuckcmsModuleEcommerce;

use Chuckbe\ChuckcmsModuleEcommerce\Commands\InstallModuleEcommerce;
use Illuminate\Support\ServiceProvider;

class ChuckcmsModuleEcommerceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        $this->publishes([
            __DIR__.'/resources' => public_path('chuckbe/chuckcms-module-ecommerce'),
        ], 'chuckcms-module-ecommerce-public');

        $this->publishes([
            __DIR__ . '/../config/chuckcms-module-ecommerce.php' => config_path('chuckcms-module-ecommerce.php'),
        ], 'chuckcms-module-ecommerce-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModuleEcommerce::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->loadViewsFrom(__DIR__.'/views', 'chuckcms-module-ecommerce');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/chuckcms-module-ecommerce.php', 'chuckcms-module-ecommerce'
        );
    }
}
