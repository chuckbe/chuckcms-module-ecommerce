<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Providers;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;

use Exception;
use Illuminate\Support\ServiceProvider;

class ChuckCustomerServiceProvider extends ServiceProvider
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
        $this->app->singleton('ChuckCustomer',function(){
            $user = \Auth::user();
            if($user == null) {
                throw new Exception('Whoops! User not logged in...');
            }
            $customer = Customer::where('user_id', \Auth::user()->id)->first();
            if($customer == null) {
                throw new Exception('Whoops! No customer profile found for user...');
            }
            return new \Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors\Customer($customer, \App::make(CustomerRepository::class));
        });
    }
}