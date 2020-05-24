<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
                ),
                'sixth' => array(
                    'name' => 'Instellingen',
                    'icon' => true,
                    'icon_data' => 'cpu',
                    'route' => 'dashboard.module.ecommerce.settings.index',
                    'has_submenu' => false,
                    'submenu' => null
                )
            )
        );
        $json['settings'] = [];
        $json['settings']['general']['supported_currencies'] = ['EUR'];
        $json['settings']['general']['featured_currency'] = 'EUR';
        $json['settings']['general']['decimals'] = 2;
        $json['settings']['general']['decimals_separator'] = ',';
        $json['settings']['general']['thousands_separator'] = '.';

        $json['settings']['layout']['template'] = 'chuckcms-template-london';

        $json['settings']['customer']['groups'] = [
            'guest' => [
                'name' => 'Guest',
                'guest' => true,
                'default' => false,
                'b2b' => false,
                'show_tax' => true,
                'required' => []
            ],
            'default' => [
                'name' => 'Consumer',
                'guest' => false,
                'default' => true,
                'b2b' => false,
                'show_tax' => true,
                'required' => []
            ],
            'business' => [
                'name' => 'Business',
                'guest' => false,
                'default' => false,
                'b2b' => true,
                'show_tax' => false,
                'required' => ['customer_company_name', 'customer_company_vat']
            ]
        ];

        $json['settings']['invoice']['prefix'] = '';
        $json['settings']['invoice']['number'] = 0;

        $json['settings']['order']['minimum'] = 0;
        $json['settings']['order']['countries'] = ['BE','NL','LU'];

        $json['settings']['order']['statuses'] = [
            'new' => [
                'display_name' => ['nl' => 'Nieuwe bestelling', 'en' => 'New order'],
                'short' => ['nl' => 'Nieuw', 'en' => 'New'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'awaiting' => [
                'display_name' => ['nl' => 'In afwachting van betaling', 'en' => 'Awaiting payment'],
                'short' => ['nl' => 'Afwachting', 'en' => 'Awaiting'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'canceled' => [
                'display_name' => ['nl' => 'Bestelling geannuleerd', 'en' => 'Order canceled'],
                'short' => ['nl' => 'Geannuleerd', 'en' => 'Canceled'],
                'send_email' => true,
                'email' => [
                    'template' => 'chuckcms-template-london::templates.chuckcms-template-london.ecommerce.mails.canceled',
                    'data' => [
                        'intro' => [
                            'type' => 'text',
                            'value' => [
                                'nl' => 'Uw bestelling werd zonet geannuleerd',
                                'en' => 'Your order just got canceled'
                            ],
                            'required' => true,
                            'validation' => 'required|max:255'
                        ],
                        'body' => [
                            'type' => 'textarea',
                            'value' => [
                                'nl' => 'Helaas werd je bestelling zonet geannuleerd. \n\n Als wij dit voor je hebben gedaan en je moet nog geld terugkrijgen dan is dit reeds onderweg naar de rekening waarmee je hebt betaald, is dit met Paypal dan krijg je een bijgeschreven krediet. \n\n ',
                                'en' => 'Unfortunately your order just got canceled. \n\n If we did this for you and you are eligible for a refund than your money is already on it\'s way to you.'
                            ],
                            'required' => true,
                            'validation' => 'required'
                        ],
                    ]
                ],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'error' => [
                'display_name' => ['nl' => 'Betalingsfout', 'en' => 'Payment Error'],
                'short' => ['nl' => 'Betalingsfout', 'en' => 'Payment Error'],
                'send_email' => true,
                'email' => [
                    'template' => 'chuckcms-template-london::templates.chuckcms-template-london.ecommerce.mails.canceled',
                    'data' => [
                        'intro' => [
                            'type' => 'text',
                            'value' => [
                                'nl' => 'Er is iets misgegaan met uw betaling',
                                'en' => 'Something went wrong with your payment'
                            ],
                            'required' => true,
                            'validation' => 'required|max:255'
                        ],
                        'body' => [
                            'type' => 'textarea',
                            'value' => [
                                'nl' => 'Helaas is er zonet iets misgegaan met uw betaling. \n\n Maak je geen zorgen, wij hebben je bestelling alsnog opgeslagen. Als je je bestelling wilt afwerken druk dan op de betaalknop hieronder. \n\n ',
                                'en' => 'Unfortunately something went wrong with your payment. \n\n No worries though, we saved your order just in case. If you would like to continue your order press the payment button just below. \n\n '
                            ],
                            'required' => true,
                            'validation' => 'required'
                        ],
                    ]
                ],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],
            'payment' => [
                'display_name' => ['nl' => 'Betaald', 'en' => 'Paid'],
                'short' => ['nl' => 'Betaald', 'en' => 'Paid'],
                'send_email' => true,
                'email' => [
                    'template' => 'chuckcms-template-london::templates.chuckcms-template-london.ecommerce.mails.canceled',
                    'data' => [
                        'intro' => [
                            'type' => 'text',
                            'value' => [
                                'nl' => 'Bedankt voor uw bestelling',
                                'en' => 'Thank you for your order'
                            ],
                            'required' => true,
                            'validation' => 'required|max:255'
                        ],
                        'body' => [
                            'type' => 'textarea',
                            'value' => [
                                'nl' => 'Hieronder vind je nog eens een overzicht van wat je hebt besteld. \n\n Heb je gekozen voor levering? Dan beginnen wij alvast met je bestelling voor te bereiden. Je krijgt nog een bevestiginsmail wanneer je pakketje onderweg is.',
                                'en' => 'Below you will find a summary of what you just ordered. \n\n Did you choose for delivery? Then we are already preparing your order. You will receive another confirmation email when your package is on its way.'
                            ],
                            'required' => true,
                            'validation' => 'required'
                        ],
                    ]
                ],
                'invoice' => true,
                'delivery' => false,
                'paid' => true
            ],
            'preparation' => [
                'display_name' => ['nl' => 'Wordt voorbereid', 'en' => 'Being prepared'],
                'short' => ['nl' => 'Voorbereiding', 'en' => 'Preparation'],
                'send_email' => false,
                'email' => [],
                'invoice' => true,
                'delivery' => false,
                'paid' => true
            ],
            'shipping' => [
                'display_name' => ['nl' => 'Verzonden', 'en' => 'Shipped'],
                'short' => ['nl' => 'Verzonden', 'en' => 'Shipped'],
                'send_email' => true,
                'email' => [
                    'template' => 'chuckcms-template-london::templates.chuckcms-template-london.ecommerce.mails.canceled',
                    'data' => [
                        'intro' => [
                            'type' => 'text',
                            'value' => [
                                'nl' => 'Bestelling is verzonden',
                                'en' => 'Order is shipped'
                            ],
                            'required' => true,
                            'validation' => 'required|max:255'
                        ],
                        'body' => [
                            'type' => 'textarea',
                            'value' => [
                                'nl' => 'Je bestelling is verzonden en onderweg naar jou.',
                                'en' => 'Your order is shipped and on its way to you.'
                            ],
                            'required' => true,
                            'validation' => 'required'
                        ],
                    ]
                ],
                'invoice' => true,
                'delivery' => true,
                'paid' => true
            ],
            'delivery' => [
                'display_name' => ['nl' => 'Afgeleverd', 'en' => 'Delivered'],
                'short' => ['nl' => 'Afgeleverd', 'en' => 'Delivered'],
                'send_email' => false,
                'email' => [],
                'invoice' => true,
                'delivery' => true,
                'paid' => true
            ],
            'backorder' => [
                'display_name' => ['nl' => 'Backorder', 'en' => 'Backorder'],
                'short' => ['nl' => 'Backorder', 'en' => 'Backorder'],
                'send_email' => false,
                'email' => [],
                'invoice' => false,
                'delivery' => false,
                'paid' => false
            ],

        ];

        $json['settings']['shipping']['carriers']['default'] = [
            'name' => 'Standaard',
            'transit_time' => 'levering binnen 48u',
            'image' => null,
            'cost' => "0.000000",
            'countries' => ['BE', 'LU'],
            'default' => true
        ];

        $json['settings']['integrations']['mollie'] = [];
        $json['settings']['integrations']['mollie']['key'] = null;
        $json['settings']['integrations']['mollie']['methods'] = ['bancontact','belfius','creditcard','ideal','inghomepay','kbc','paypal'];

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

        // Permissions
        Permission::firstOrCreate(['name' => 'show account']);

        // create roles and assign created permissions
        $role = Role::firstOrCreate(['name' => 'customer']);
        $role->redirect = 'mijn-account';
        $role->save();
        $role->revokePermissionTo(Permission::all());
        $role->givePermissionTo([
            'show account',
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
