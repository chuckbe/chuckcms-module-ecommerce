<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;
use ChuckSite;

class UpdateCarriersToMultilanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:update-carriers-multi-language';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates the ChuckCMS Ecommerce Module.';

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
        $ecommerce = $this->moduleRepository->get('chuckcms-module-ecommerce');
        $json = $ecommerce->json;

        $carriers = $json['settings']['carriers'];

        foreach ($carriers as $carrierKey => $carrier) {
            $name = $carrier[$carrierKey]['name'];
            $transit_time = $carrier[$carrierKey]['transit_time'];

            $carriers[$carrierKey]['name'] = [];
            $carriers[$carrierKey]['transit_time'] = [];
            foreach (ChuckSite::getSupportedLocales() as $langKey => $langValue) {
                $carriers[$carrierKey]['name'][$langKey] = $name;
                $carriers[$carrierKey]['transit_time'][$langKey] = $transit_time;
            }
        }

        $json['settings']['carriers'] = $carriers;
        $ecommerce->json = $json;
        $ecommerce->update();
        

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
        $this->info('Ecommerce Carriers updated');
        $this->info(' ');
    }
}
