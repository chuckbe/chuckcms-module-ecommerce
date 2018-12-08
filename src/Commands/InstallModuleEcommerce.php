<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;

class InstallModuleEcommerce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command installs the ChuckCMS Ecommerce Module.';

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
        $name = 'ChuckCMS Ecommerce Module';
        $slug = 'chuckcms-module-ecommerce';
        $hintpath = 'chuckcms-module-ecommerce';
        $path = 'chuckbe/chuckcms-module-ecommerce';
        $type = 'module';
        $version = '0.1.0';
        $author = 'Karel Brijs (karel@chuck.be)';

        $json = [];
        $json['admin']['show_in_menu'] = true;
        $json['admin']['menu'] = array(
            'name' => 'E-commerce',
            'icon' => 'shopping-cart',
            'route' => '#',
            'has_submenu' => true,
            'submenu' => array(
                'first' => array(
                    'name' => 'Overzicht',
                    'icon' => true,
                    'icon_data' => 'shopping-bag',
                    'route' => 'dashboard.module.ecommerce.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'second' => array(
                    'name' => 'Bestellingen',
                    'icon' => true,
                    'icon_data' => 'inbox',
                    'route' => 'dashboard.module.ecommerce.orders.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'third' => array(
                    'name' => 'Producten',
                    'icon' => true,
                    'icon_data' => 'tag',
                    'route' => 'dashboard.module.ecommerce.products.index',
                    'has_submenu' => true,
                    'submenu' => array(
                        'first' => array(
                            'name' => 'Overzicht',
                            'icon' => false,
                            'icon_data' => 'Ov',
                            'route' => 'dashboard.module.ecommerce.products.index',
                            'has_submenu' => false,
                            'submenu' => null
                        ),
                        'second' => array(
                            'name' => 'Collecties',
                            'icon' => false,
                            'icon_data' => 'Co',
                            'route' => 'dashboard.module.ecommerce.collections.index',
                            'has_submenu' => false,
                            'submenu' => null
                        ),
                        'third' => array(
                            'name' => 'Merken',
                            'icon' => false,
                            'icon_data' => 'Br',
                            'route' => 'dashboard.module.ecommerce.brands.index',
                            'has_submenu' => false,
                            'submenu' => null
                        ),
                        'fourth' => array(
                            'name' => 'Attributen',
                            'icon' => false,
                            'icon_data' => 'At',
                            'route' => 'dashboard.module.ecommerce.attributes.index',
                            'has_submenu' => false,
                            'submenu' => null
                        )
                    )
                ),
                'fourth' => array(
                    'name' => 'Klanten',
                    'icon' => true,
                    'icon_data' => 'user',
                    'route' => 'dashboard.module.ecommerce.customers.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'fifth' => array(
                    'name' => 'Kortingen',
                    'icon' => true,
                    'icon_data' => 'gift',
                    'route' => 'dashboard.module.ecommerce.discounts.index',
                    'has_submenu' => false,
                    'submenu' => null
                )
            )
        );

        // create the module
        $module = $this->moduleRepository->createFromArray([
            'name' => $name,
            'slug' => $slug,
            'hintpath' => $hintpath,
            'path' => $path,
            'type' => $type,
            'version' => $version,
            'author' => $author,
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
        $this->info('Module installed: ChuckCMS Ecommerce');
        $this->info(' ');

        
    }
}
