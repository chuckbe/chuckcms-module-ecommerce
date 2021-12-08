<?php

namespace Chuckbe\ChuckcmsModuleEcommerce;

use Chuckbe\ChuckcmsModuleEcommerce\Commands\InstallModuleEcommerce;
use Chuckbe\ChuckcmsModuleEcommerce\Commands\AddAwaitingTransferStatus;
use Chuckbe\ChuckcmsModuleEcommerce\Commands\UpdateCarriersToMultilanguage;
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

        $this->publishes(
            [
                __DIR__ . '/../config/chuckcms-module-ecommerce.php' => config_path('chuckcms-module-ecommerce.php'),
                __DIR__ . '/../config/cart.php' => config_path('cart.php'),
            ], 'chuckcms-module-ecommerce-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModuleEcommerce::class,
                AddAwaitingTransferStatus::class,
                UpdateCarriersToMultilanguage::class,
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
        $this->app['Chuckbe\Chuckcms\Models\User'] = $this->app['Chuckbe\ChuckcmsModuleEcommerce\Models\User'];
        
        $this->loadViewsFrom(__DIR__.'/views', 'chuckcms-module-ecommerce');

        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckCollectionServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckEcommerceServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckCustomerServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckProductServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckEventServiceProvider');
        $this->app->register('Chuckbe\ChuckcmsModuleEcommerce\Providers\ChuckCartServiceProvider');

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('ChuckEcommerce', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Ecommerce');
        $loader->alias('ChuckCollection', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Collection');
        $loader->alias('ChuckCustomer', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Customer');
        $loader->alias('ChuckProduct', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Product');
        $loader->alias('ChuckCart', 'Chuckbe\ChuckcmsModuleEcommerce\Facades\Cart');

        $this->mergeConfigFrom(__DIR__ . '/../config/chuckcms-module-ecommerce.php', 'chuckcms-module-ecommerce');
        $this->mergeConfigFrom(__DIR__ . '/../config/cart.php', 'cart');
    }
}
