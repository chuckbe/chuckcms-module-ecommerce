<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;
use ChuckCollection;
use ChuckProduct;

class AddIsPosAvailableToCollectionsAndProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:add-is-pos-available';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds the - is_pos_available - key to the Collections and Products repeaters.';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $moduleRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        parent::__construct();

        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $collections = ChuckCollection::all();

        foreach ($collections as $collection) {
            $json = $collection->json;
            $json['is_pos_available'] = true;
            $collection->json = $json;
            $collection->update();
        }

        $products = ChuckProduct::all();

        foreach ($products as $product) {
            $json = $product->json;
            $json['is_pos_available'] = true;
            $product->json = $json;
            $product->update();
        }


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
