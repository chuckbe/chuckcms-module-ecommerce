<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;
use ChuckSite;

class AddAwaitingTransferStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:add-awaiting-transfer-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds the - Awaiting Transfer - status to the Ecommerce Module.';

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
        $supportedLocales = ChuckSite::getSupportedLocales();
        $ecommerce = $this->moduleRepository->get('chuckcms-module-ecommerce');
        $json = $ecommerce->json;

        $statuses = $json['settings']['order']['statuses'];

        $statuses['awaiting_transfer'] = [];

        $statuses['awaiting_transfer']['display_name'] = [];
        $statuses['awaiting_transfer']['short'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['display_name'][$langKey] = 'In afwachting van overschrijving';
                $statuses['awaiting_transfer']['short'][$langKey] = 'Afwachting overschrijving';
            } else {
                $statuses['awaiting_transfer']['display_name'][$langKey] = 'Awaiting wire transfer';
                $statuses['awaiting_transfer']['short'][$langKey] = 'Awaiting wire transfer';
            }
        }

        $statuses['awaiting_transfer']['send_email'] = true;

        $statuses['awaiting_transfer']['email'] = [];
        $statuses['awaiting_transfer']['email']['customer'] = [];
        $statuses['awaiting_transfer']['email']['customer']['to'] = '[%ORDER_EMAIL%]';
        $statuses['awaiting_transfer']['email']['customer']['to_name'] = '[%ORDER_SURNAME%] [%ORDER_NAME%]';
        $statuses['awaiting_transfer']['email']['customer']['cc'] = null;
        $statuses['awaiting_transfer']['email']['customer']['bcc'] = null;
        $statuses['awaiting_transfer']['email']['customer']['template'] = 'chuckcms-module-ecommerce::emails.default';
        $statuses['awaiting_transfer']['email']['customer']['logo'] = true;
        $statuses['awaiting_transfer']['email']['customer']['send_delivery_note'] = false;

        $statuses['awaiting_transfer']['email']['customer']['data'] = [];
        
        $statuses['awaiting_transfer']['email']['customer']['data']['subject'] = [];
        $statuses['awaiting_transfer']['email']['customer']['data']['subject']['type'] = 'text';
        $statuses['awaiting_transfer']['email']['customer']['data']['subject']['value'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['email']['customer']['data']['subject']['value'][$langKey] = 'Uw bestelling #[%ORDER_NUMBER%] wacht op uw overschrijving';
            } else {
                $statuses['awaiting_transfer']['email']['customer']['data']['subject']['value'][$langKey] = 'Your order #[%ORDER_NUMBER%] awaits your wire transfer';
            }
        }
        $statuses['awaiting_transfer']['email']['customer']['data']['subject']['required'] = true;
        $statuses['awaiting_transfer']['email']['customer']['data']['subject']['validation'] = 'required';


        $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader'] = [];
        $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader']['type'] = 'text';
        $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader']['value'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader']['value'][$langKey] = 'Uw bestelling met bestelnummer #[%ORDER_NUMBER%] wacht op uw overschrijving. In deze mail vindt u meer informatie terug.';
            } else {
                $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader']['value'][$langKey] = 'Your order #[%ORDER_NUMBER%] awaits your wire transfer. You can find more information in this email.';
            }
        }
        $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader']['required'] = true;
        $statuses['awaiting_transfer']['email']['customer']['data']['hidden_preheader']['validation'] = 'required';


        $statuses['awaiting_transfer']['email']['customer']['data']['intro'] = [];
        $statuses['awaiting_transfer']['email']['customer']['data']['intro']['type'] = 'textarea';
        $statuses['awaiting_transfer']['email']['customer']['data']['intro']['value'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['email']['customer']['data']['intro']['value'][$langKey] = 'Beste [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Bedankt voor uw bestelling. Om deze te vervolledigen vragen u om de volgende overschrijving uit te voeren. <br><br>[%ORDER_WIRE_TRANSFER%]';
            } else {
                $statuses['awaiting_transfer']['email']['customer']['data']['intro']['value'][$langKey] = 'Dear [%ORDER_SURNAME%] [%ORDER_NAME%]<br><br>Thank you for your order. To complete this order we ask you to wire transfer us with the following details. <br><br>[%ORDER_WIRE_TRANSFER%]';
            }
        }
        $statuses['awaiting_transfer']['email']['customer']['data']['intro']['required'] = true;
        $statuses['awaiting_transfer']['email']['customer']['data']['intro']['validation'] = 'required';


        $statuses['awaiting_transfer']['email']['customer']['data']['body_title'] = [];
        $statuses['awaiting_transfer']['email']['customer']['data']['body_title']['type'] = 'text';
        $statuses['awaiting_transfer']['email']['customer']['data']['body_title']['value'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['email']['customer']['data']['body_title']['value'][$langKey] = 'Uw Bestelling';
            } else {
                $statuses['awaiting_transfer']['email']['customer']['data']['body_title']['value'][$langKey] = 'Your Order';
            }
        }
        $statuses['awaiting_transfer']['email']['customer']['data']['body_title']['required'] = true;
        $statuses['awaiting_transfer']['email']['customer']['data']['body_title']['validation'] = 'required';


        $statuses['awaiting_transfer']['email']['customer']['data']['body'] = [];
        $statuses['awaiting_transfer']['email']['customer']['data']['body']['type'] = 'textarea';
        $statuses['awaiting_transfer']['email']['customer']['data']['body']['value'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['email']['customer']['data']['body']['value'][$langKey] = 'Hieronder vind je nogmaals een overzicht terug van jouw bestelling. <br><br> <b>Verzending:</b> [%ORDER_CARRIER_NAME%] <br> <b>Verzendtijd:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Overzicht: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Verzendkosten</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Totaal</b>: [%ORDER_FINAL%] <br><br> <b>Facturatie adres: </b> <br> Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Bedrijf: [%ORDER_COMPANY%] <br> BTW: [%ORDER_COMPANY_VAT%] <br> Adres: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Verzendadres:</b><br>Naam: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Adres:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]';
            } else {
                $statuses['awaiting_transfer']['email']['customer']['data']['body']['value'][$langKey] = 'Below you will find another summary of your order. <br><br> <b>Shipping:</b> [%ORDER_CARRIER_NAME%] <br> <b>Transit time:</b> [%ORDER_CARRIER_TRANSIT_TIME%] <br><br> <b>Order: </b> <br> [%ORDER_PRODUCTS%] <br> <b>Shipping fees</b>: [%ORDER_SHIPPING_TOTAL%] <br><br> <b>Total</b>: [%ORDER_FINAL%] <br><br> <b>Invoice address: </b> <br> Name: [%ORDER_SURNAME%] [%ORDER_NAME%] <br> E-mail: [%ORDER_EMAIL%] <br> Tel: [%ORDER_TELEPHONE%] <br> Company: [%ORDER_COMPANY%] <br> VAT: [%ORDER_COMPANY_VAT%] <br> Address: <br>[%ORDER_BILLING_STREET%] [%ORDER_BILLING_HOUSENUMBER%], <br>[%ORDER_BILLING_POSTALCODE%] [%ORDER_BILLING_CITY%], [%ORDER_BILLING_COUNTRY%] <br><br> <b>Shipping address:</b><br>Name: [%ORDER_SURNAME%] [%ORDER_NAME%] <br>Address:<br>[%ORDER_SHIPPING_STREET%] [%ORDER_SHIPPING_HOUSENUMBER%], <br>[%ORDER_SHIPPING_POSTALCODE%] [%ORDER_SHIPPING_CITY%], [%ORDER_SHIPPING_COUNTRY%]';
            }
        }
        $statuses['awaiting_transfer']['email']['customer']['data']['body']['required'] = true;
        $statuses['awaiting_transfer']['email']['customer']['data']['body']['validation'] = 'required';


        $statuses['awaiting_transfer']['email']['customer']['data']['footer'] = [];
        $statuses['awaiting_transfer']['email']['customer']['data']['footer']['type'] = 'textarea';
        $statuses['awaiting_transfer']['email']['customer']['data']['footer']['value'] = [];
        foreach ($supportedLocales as $langKey => $langValue) {
            if ($langKey == 'nl') {
                $statuses['awaiting_transfer']['email']['customer']['data']['footer']['value'][$langKey] = 'Heeft u vragen over uw bestelling? U kan ons steeds contacteren.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name');
            } else {
                $statuses['awaiting_transfer']['email']['customer']['data']['footer']['value'][$langKey] = 'Do you have any other questions about this order? You can always reach us.<br><br><a href="mailto:' . config('chuckcms-module-ecommerce.company.email') . '">' . config('chuckcms-module-ecommerce.company.email') . '</a><br><br>' . config('chuckcms-module-ecommerce.company.name');
            }
        }
        $statuses['awaiting_transfer']['email']['customer']['data']['footer']['required'] = true;
        $statuses['awaiting_transfer']['email']['customer']['data']['footer']['validation'] = 'required';
        

        $statuses['awaiting_transfer']['invoice'] = false;
        $statuses['awaiting_transfer']['delivery'] = false;
        $statuses['awaiting_transfer']['paid'] = false;



        $json['settings']['order']['statuses'] = $statuses;
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
        $this->info('Ecommerce Status Awaiting Transfer Added');
        $this->info(' ');
    }
}
