<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Providers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;

use Exception;
use Illuminate\Support\ServiceProvider;

class ChuckCollectionServiceProvider extends ServiceProvider
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
        $this->app->bind('ChuckCollection',function(){            
            return new \Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors\Collection(\App::make(CollectionRepository::class));
        });
    }
}