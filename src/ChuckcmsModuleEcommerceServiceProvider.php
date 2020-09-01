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
        config([
            'auth.providers.users.model' => \Chuckbe\ChuckcmsModuleEcommerce\Models\User::class
        ]);

        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        //php artisan vendor:publish --tag=chuckcms-module-ecommerce-public --force
        $this->publishes([__DIR__.'/resources' => public_path('chuckbe/chuckcms-module-ecommerce'),
        ], 'chuckcms-module-ecommerce-public');

        $this->publishes([__DIR__ . '/../config/chuckcms-module-ecommerce.php' => config_path('chuckcms-module-ecommerce.php'),
        ], 'chuckcms-module-ecommerce-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModuleEcommerce::class,
            ]);
        }

        //\Illuminate\Support\Facades\Event::listen('Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid', 'Chuckbe\ChuckcmsModuleEcommerce\Listeners\UpdateStatusToPaid');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->loadViewsFrom(__DIR__.'/views', 'chuckcms-module-ecommerce');

        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckCollectionServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckEcommerceServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckCustomerServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckProductServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckEventServiceProvider');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('ChuckEcommerce', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Ecommerce');
        $loader->alias('ChuckCollection', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Collection');
        $loader->alias('ChuckCustomer', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Customer');
        $loader->alias('ChuckProduct', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Product');

        $this->mergeConfigFrom(__DIR__ . '/../config/chuckcms-module-ecommerce.php', 'chuckcms-module-ecommerce');
    }
}
