<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Commands;


use Illuminate\Console\Command;
use Chuckbe\Chuckcms\Models\Repeater;
use Maatwebsite\Excel\Facades\Excel;
use Chuckbe\ChuckcmsModuleEcommerce\Imports\FbImport;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToCollection;
use ChuckEcommerce;
use ChuckCollection;
use ChuckSite;
use Str;

class ImportFacebookCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-ecommerce:importfacebookcategories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command imports the ChuckCMS Ecommerce Module.';

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
            '_af_ZA',
            '_am_ET',
            '_ar_AR',
            '_bg_BG',
            '_bn_IN',
            '_cs_CZ',
            '_da_DK',
            '_de_DE',
            '_el_GR',
            '_en_GB',
            '_es_ES',
            '_es_LA',
            '_fi_FI',
            '_fr_CA',
            '_fr_FR',
            '_ha_NG',
            '_he_IL',
            '_hi_IN',
            '_hr_HR',
            '_hu_HU',
            '_id_ID',
            '_it_IT',
            '_ja_JP',
            '_km_KH',
            '_ko_KR',
            '_mr_IN',
            '_ms_MY',
            '_mt_MT',
            '_nb_NO',
            '_nl_NL',
            '_pl_PL',
            '_pt_BR',
            '_pt_PT',
            '_ro_RO',
            '_ru_RU',
            '_sk_SK',
            '_sl_SL',
            '_sv_SE',
            '_sw_KE',
            '_th_TH',
            '_tl_PH',
            '_tr_TR',
            'ur_PK',
            '_vi_VN',
            '_zh_CN'
        ];
        $languages = [
            'Afrikaans',
            'አማርኛ',
            'عربي',
            'български',
            'বাংলা',
            'čeština',
            'Dansk',
            'Deutsch',
            'Ελληνικά',
            'English [GB]',
            'Español (España)',
            'Español (Latinoamérica)',
            'Suomalainen',
            'Français (Canada)',
            'Français (France)',
            'Hausa',
            'עִברִית',
            'हिंदी',
            'Hrvatski',
            'Magyar',
            'Bahasa Indonesia',
            'Italiano',
            '日本',
            'ខ្មែរ',
            '한국인',
            'मराठी',
            'Melayu',
            'Malti',
            'Norsk',
            'Nederlands',
            'Polskie',
            'Português (Brasil)',
            'Portuguese (Portugal)',
            'Românesc',
            'Русский',
            'Slovenská',
            'Slovenski',
            'Svenska (Sverige)',
            'Kiswahili (Kenya)',
            'ไทย',
            'Tagalog (Filipino)',
            'Türk',
            'اردو',
            'Tiếng Việt',
            '中国人',
            '中文（香港)',
            '中文（台灣)',
        ];
        $choice = $this->choice("Choose a language", $languages);
        $index = array_search($choice, $languages);
        $choosen_lang = $lang_codes[$index];
        $this->info('working on creating repeater');
        $this->info('hold on tight!');
        Excel::import(new FbImport, public_path('chuckbe/chuckcms-module-ecommerce/fb_categories/fb_product_categories'.$choosen_lang.'.csv'));

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
