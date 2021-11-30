<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;


use Illuminate\Console\Command;
use Chuckbe\Chuckcms\Models\Repeater;
use Illuminate\Support\Facades\File; 
use Maatwebsite\Excel\Facades\Excel;
use Chuckbe\ChuckcmsModuleEcommerce\Imports\GpcImport;

class ImportGoogleCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:importgooglecategories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates a repeater and imports the Google product categories in that repeater.';

    /**
     * The module repository implementation.
     *
     * @var Repeater
     */
    protected $repeater;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Repeater $repeater)
    {
        parent::__construct();

        $this->repeater = $repeater;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lang_codes = [
            'cs-CZ',
            'da-DK',
            'de-CH',
            'de-DE',
            'el-GR',
            'en-GB',
            'es-ES',
            'fi-FI',
            'fr-CH',
            'fr-FR',
            'it-CH',
            'it-IT',
            'ja-JP',
            'nl-NL',
            'no-NO',
            'pl-PL',
            'pt-BR',
            'ru-RU',
            'sv-SE',
            'tr-TR'
        ];
        $languages = [
            'čeština',
            'Dansk',
            'Deutsch (Schweiz)',
            'Deutsch (Deutschland)',
            'Ελληνικά',
            'English [GB]',
            'Español (España)',
            'Suomalainen',
            'Français (Suisse)',
            'Français (France)',
            'Italiano (Svizzera)',
            'Italiano (Italia)',
            '日本',
            'Nederlands',
            'Norsk',
            'Polskie',
            'Portuguese',
            'Русский',
            'Svenska',
            'Türk',
        ];
        $choice = $this->choice("Choose a language", $languages);
        $index = array_search($choice, $languages);
        $choosen_lang = $lang_codes[$index];
        $this->info('working on creating repeater');
        $this->info('hold on tight!');
        // Excel::import(new GpcImport, public_path('chuckbe/chuckcms-module-ecommerce/google_categories/taxonomy-with-ids.'.$choosen_lang.'.xls'));

        $xls = file_get_contents('https://cdn.chuck.be/chuckcms-module-ecommerce/google_categories/taxonomy-with-ids.'.$choosen_lang.'.xls');
        file_put_contents(public_path('chuckbe/chuckcms-module-ecommerce/gpc.xls'), $xls);
        Excel::import(new GpcImport, public_path('chuckbe/chuckcms-module-ecommerce/gpc.xls'));
        File::delete(public_path('chuckbe/chuckcms-module-ecommerce/gpc.xls'));


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
        $this->info('Repeater created: ChuckCMS Ecommerce');
        $this->info(' ');
    }
}
