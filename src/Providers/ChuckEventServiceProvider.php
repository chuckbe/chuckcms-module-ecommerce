<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Chuckbe\ChuckcmsModuleEcommerce\Listeners\UpdateStatusToPaid;
use Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid;
use Illuminate\Support\Facades\Event;

class ChuckEventServiceProvider extends ServiceProvider
{

    protected $listen = [
        'Chuckbe\ChuckcmsModuleEcommerce\Events\OrderWasPaid' => [
            'Chuckbe\ChuckcmsModuleEcommerce\Listeners\UpdateStatusToPaid'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}