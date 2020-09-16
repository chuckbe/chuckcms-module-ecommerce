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
        $version = '0.1.10';
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

        $json['settings']['layout']['template'] = 'chuckcms-template-starter';

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
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] is verzonden',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] is onderweg. In deze mail vindt u meer informatie over uw bestelling terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling is onderweg. Uw bestelling wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:'.config('chuckcms-module-ecommerce.company.email').'">'.config('chuckcms-module-ecommerce.company.email').'</a><br><br>'.config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
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
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] is verzonden',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] is onderweg. In deze mail vindt u meer informatie over uw bestelling terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling is onderweg. Uw bestelling wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:'.config('chuckcms-module-ecommerce.company.email').'">'.config('chuckcms-module-ecommerce.company.email').'</a><br><br>'.config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
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
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] is verzonden',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] is onderweg. In deze mail vindt u meer informatie over uw bestelling terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling is onderweg. Uw bestelling wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:'.config('chuckcms-module-ecommerce.company.email').'">'.config('chuckcms-module-ecommerce.company.email').'</a><br><br>'.config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
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
                    'customer' => [
                        'to' => '[%ORDER_EMAIL%]',
                        'to_name' => '[%ORDER_SURNAME%] [%ORDER_NAME%]',
                        'cc' => null,
                        'bcc' => null,
                        'template' => 'chuckcms-module-ecommerce::emails.default',
                        'logo' => true,
                        'data' => [
                            'subject' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling #[%ORDER_NUMBER%] is verzonden',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'hidden_preheader' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] is onderweg. In deze mail vindt u meer informatie over uw bestelling terug.',
                                    'en' => 'Your order #[%ORDER_NUMBER%] was shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'intro' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Uw bestelling is onderweg. Uw bestelling wordt volgende werkdag geleverd tussen 9:00u en 19:00u. Is er niemand thuis? Dan proberen we het de dag erna nog eens, maak u geen zorgen. Heeft u nog vragen? Neem gerust contact met ons op.',
                                    'en' => 'Order is shipped'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body_title' => [
                                'type' => 'text',
                                'value' => [
                                    'nl' => 'Uw Bestelling',
                                    'en' => 'Your Order'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'body' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]',
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                            'footer' => [
                                'type' => 'textarea',
                                'value' => [
                                    'nl' => 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:'.config('chuckcms-module-ecommerce.company.email').'">'.config('chuckcms-module-ecommerce.company.email').'</a><br><br>'.config('chuckcms-module-ecommerce.company.name'),
                                    'en' => 'Your order is shipped and on its way to you.'
                                ],
                                'required' => true,
                                'validation' => 'required'
                            ],
                        ]
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
