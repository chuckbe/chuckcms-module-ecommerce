<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Customer;
use Illuminate\Console\Command;

class MakeGuestCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:make-guest-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds a guest customer.';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $customerRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        parent::__construct();

        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $json = [];
        $json['group'] = $this->customerRepository->guestGroup();

        $customer = Customer::create([
            'surname' => "Guest",
            'name' => "Guest",
            'email' => "guest@guest.com",
            'tel' => null,
            'json' => $json
        ]);


        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('.... AWESOME ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('....   JOB   ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info(' ');
        $this->info('Is Pos Available key added to Collections and Products.');
        $this->info(' ');
    }
}
