<?php

return [

    'company' => [
        'name' => 'ChuckCMS',
        'vat' => 'BE0XXX.XXX.XXX',
        'address1' => 'Berlaarsestraat 10',
        'address2' => '2500 Lier',
        'email' => 'hello@chuck.be'
    ],

    'order' => [
        'payment_description' => 'Bestelling #',
        'redirect_url' => 'bedankt'
    ],

	'pages' => [
		'product_detail' => 'chuckcms-module-ecommerce::frontend.products.detail',

	],

    'blade_layouts' => [
            'cart_detail' => '_header_cart',
            'cart_overview' => '_cart_overview',
            'cart_page' => 'cart'
    ],

	'vat' => [
		'be21' 	=> ['type' => 'BTW BE 21%', 'amount' => 21, 'default' => true],
		'be12' 	=> ['type' => 'BTW BE 12%', 'amount' => 12, 'default' => false],
		'be6' 	=> ['type' => 'BTW BE 6%', 'amount' => 6, 'default' => false],
		'be0' 	=> ['type' => 'BTW BE 0%', 'amount' => 0, 'default' => false]
	],

	'attributes' => [
		'slug' 	=> 'attributes',
		'url' => 'attribute/',
		'page' => 'chuckcms-template-starter::templates.chuckcms-template-starter.default'
	],

	'brands' => [
		'slug' 	=> 'brands',
		'url' => 'brands/',
		'page' => 'chuckcms-template-starter::templates.chuckcms-template-starter.default'
	],

	'collections' => [
		'slug' 	=> 'collections',
		'url' => 'collections/',
		'page' => 'chuckcms-template-starter::templates.chuckcms-template-starter.default'
	],

    'discounts' => [
        'slug'  => 'discounts',
        'url' => 'discounts/',
        'page' => 'chuckcms-template-starter::templates.chuckcms-template-starter.default'
    ],

	'products' => [
		'slug' 	=> 'products',
		'url' => 'products/'
	],

	'auth' => [
		'redirect' => [
			'user' => '/dashboard',
			'moderator' => '/dashboard',
			'administrator' => '/dashboard',
			'super-admin' => '/dashboard',
			'customer' => '/account/profile',
		],

		'template' => [
			'hintpath' => 'chuckcms-template-starter'
		],
	],

    'emails' => [
        'send_confirmation' => true,
        'confirmation_subject' => 'Bevestiging van uw bestelling #',
        'send_notication' => true,
        'notification_subject' => 'Een nieuwe online bestelling #',
        'from_email' => 'hello@chuck.be',
        'from_name' => 'ChuckCMS Order',
        'to_email' => 'hello@chuck.be',
        'to_name' => 'ChuckCMS Order',
        'to_cc' => false, // false or string with emails seperated by comma
        'to_bcc' => false, // false or string with emails seperated by comma
    ],

    'integrations' => [
        'mollie' => [
            'methods' => [
                'applepay' => [
                    'display_name' => 'Apple Pay',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/applepay@2x.png'
                ],
                'bancontact' => [
                    'display_name' => 'Bancontact (mistercash)',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/bancontact@2x.png'
                ],
                'banktransfer' => [
                    'display_name' => 'Banktransfer',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/banktransfer@2x.png'
                ],
                'belfius' => [
                    'display_name' => 'Belfius',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/belfius@2x.png'
                ],
                'creditcard' => [
                    'display_name' => 'Creditcard (Visa, MasterCard, Amex)',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/creditcard@2x.png'
                ],
                'directdebit' => [
                    'display_name' => 'Direct Debit (SEPA)',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/directdebit@2x.png'
                ],
                'eps' => [
                    'display_name' => 'EPS',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/eps@2x.png'
                ],
                'giftcard' => [
                    'display_name' => 'Giftcard',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/giftcard@2x.png'
                ],
                'giropay' => [
                    'display_name' => 'Giropay',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/giropay@2x.png'
                ],
                'ideal' => [
                    'display_name' => 'Ideal',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/ideal@2x.png'
                ],
                'inghomepay' => [
                    'display_name' => 'ING',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/inghomepay@2x.png'
                ],
                'kbc' => [
                    'display_name' => 'KBC',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/kbc@2x.png'
                ],
                'klarna' => [
                    'display_name' => 'Klarna',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/klarna@2x.png'
                ],
                'mybank' => [
                    'display_name' => 'Mybank',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/mybank@2x.png'
                ],
                'paypal' => [
                    'display_name' => 'Paypal',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/paypal@2x.png'
                ],
                'paysafecard' => [
                    'display_name' => 'Paysafecard',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/paysafecard@2x.png'
                ],
                'przelewy24' => [
                    'display_name' => 'Przelewy24',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/przelewy24@2x.png'
                ],
                'sofort' => [
                    'display_name' => 'Sofort',
                    'logo' => 'chuckbe/chuckcms-module-ecommerce/images/payment-logos/sofort@2x.png'
                ]
            ]
        ]
    ],

    'currencies' => [
        // 'ALL' => 'Albania Lek',
        // 'AFN' => 'Afghanistan Afghani',
        // 'ARS' => 'Argentina Peso',
        // 'AWG' => 'Aruba Guilder',
        'AUD' => '$ Australia Dollar',
        // 'AZN' => 'Azerbaijan New Manat',
        // 'BSD' => 'Bahamas Dollar',
        // 'BBD' => 'Barbados Dollar',
        // 'BDT' => 'Bangladeshi taka',
        // 'BYR' => 'Belarus Ruble',
        // 'BZD' => 'Belize Dollar',
        // 'BMD' => 'Bermuda Dollar',
        // 'BOB' => 'Bolivia Boliviano',
        // 'BAM' => 'Bosnia and Herzegovina Convertible Marka',
        // 'BWP' => 'Botswana Pula',
        // 'BGN' => 'Bulgaria Lev',
        // 'BRL' => 'Brazil Real',
        // 'BND' => 'Brunei Darussalam Dollar',
        // 'KHR' => 'Cambodia Riel',
        // 'CAD' => 'Canada Dollar',
        // 'KYD' => 'Cayman Islands Dollar',
        // 'CLP' => 'Chile Peso',
        // 'CNY' => 'China Yuan Renminbi',
        // 'COP' => 'Colombia Peso',
        // 'CRC' => 'Costa Rica Colon',
        // 'HRK' => 'Croatia Kuna',
        // 'CUP' => 'Cuba Peso',
        // 'CZK' => 'Czech Republic Koruna',
        // 'DKK' => 'Denmark Krone',
        // 'DOP' => 'Dominican Republic Peso',
        // 'XCD' => 'East Caribbean Dollar',
        // 'EGP' => 'Egypt Pound',
        // 'SVC' => 'El Salvador Colon',
        // 'EEK' => 'Estonia Kroon',
        'EUR' => '€ Euro',
        // 'FKP' => 'Falkland Islands (Malvinas) Pound',
        // 'FJD' => 'Fiji Dollar',
        // 'GHC' => 'Ghana Cedis',
        // 'GIP' => 'Gibraltar Pound',
        // 'GTQ' => 'Guatemala Quetzal',
        // 'GGP' => 'Guernsey Pound',
        // 'GYD' => 'Guyana Dollar',
        // 'HNL' => 'Honduras Lempira',
        // 'HKD' => 'Hong Kong Dollar',
        // 'HUF' => 'Hungary Forint',
        // 'ISK' => 'Iceland Krona',
        // 'INR' => 'India Rupee',
        // 'IDR' => 'Indonesia Rupiah',
        // 'IRR' => 'Iran Rial',
        // 'IMP' => 'Isle of Man Pound',
        // 'ILS' => 'Israel Shekel',
        // 'JMD' => 'Jamaica Dollar',
        'JPY' => '¥ Japan Yen',
        // 'JEP' => 'Jersey Pound',
        // 'KZT' => 'Kazakhstan Tenge',
        // 'KPW' => 'Korea (North) Won',
        // 'KRW' => 'Korea (South) Won',
        // 'KGS' => 'Kyrgyzstan Som',
        // 'LAK' => 'Laos Kip',
        // 'LVL' => 'Latvia Lat',
        // 'LBP' => 'Lebanon Pound',
        // 'LRD' => 'Liberia Dollar',
        // 'LTL' => 'Lithuania Litas',
        // 'MKD' => 'Macedonia Denar',
        // 'MYR' => 'Malaysia Ringgit',
        // 'MUR' => 'Mauritius Rupee',
        // 'MXN' => 'Mexico Peso',
        // 'MNT' => 'Mongolia Tughrik',
        // 'MZN' => 'Mozambique Metical',
        // 'NAD' => 'Namibia Dollar',
        // 'NPR' => 'Nepal Rupee',
        // 'ANG' => 'Netherlands Antilles Guilder',
        // 'NZD' => 'New Zealand Dollar',
        // 'NIO' => 'Nicaragua Cordoba',
        // 'NGN' => 'Nigeria Naira',
        // 'NOK' => 'Norway Krone',
        // 'OMR' => 'Oman Rial',
        // 'PKR' => 'Pakistan Rupee',
        // 'PAB' => 'Panama Balboa',
        // 'PYG' => 'Paraguay Guarani',
        // 'PEN' => 'Peru Nuevo Sol',
        // 'PHP' => 'Philippines Peso',
        // 'PLN' => 'Poland Zloty',
        // 'QAR' => 'Qatar Riyal',
        // 'RON' => 'Romania New Leu',
        // 'RUB' => 'Russia Ruble',
        // 'SHP' => 'Saint Helena Pound',
        // 'SAR' => 'Saudi Arabia Riyal',
        // 'RSD' => 'Serbia Dinar',
        // 'SCR' => 'Seychelles Rupee',
        // 'SGD' => 'Singapore Dollar',
        // 'SBD' => 'Solomon Islands Dollar',
        // 'SOS' => 'Somalia Shilling',
        // 'ZAR' => 'South Africa Rand',
        // 'LKR' => 'Sri Lanka Rupee',
        // 'SEK' => 'Sweden Krona',
        // 'CHF' => 'Switzerland Franc',
        // 'SRD' => 'Suriname Dollar',
        // 'SYP' => 'Syria Pound',
        // 'TWD' => 'Taiwan New Dollar',
        // 'THB' => 'Thailand Baht',
        // 'TTD' => 'Trinidad and Tobago Dollar',
        // 'TRY' => 'Turkey Lira',
        // 'TRL' => 'Turkey Lira',
        // 'TVD' => 'Tuvalu Dollar',
        // 'UAH' => 'Ukraine Hryvna',
        'GBP' => '£ United Kingdom Pound',
        'USD' => '$ United States Dollar',
        // 'UYU' => 'Uruguay Peso',
        // 'UZS' => 'Uzbekistan Som',
        // 'VEF' => 'Venezuela Bolivar',
        // 'VND' => 'Viet Nam Dong',
        // 'YER' => 'Yemen Rial',
        // 'ZWD' => 'Zimbabwe Dollar'
    ],

    'default_country' => 'BE',

    'countries_data' => [
        "BE" => [
            "name" => "Belgium",
            "native" => "België",
            "postalcode" => [
                "max" => 4,
                "regex" => '^[1-9]{1}[0-9]{3}$'
            ],
            'vat' => [
                'max' => 12,
                'regex' => '^(BE)?0[0-9]{9}$',
                'format' => 'BE0123456789'
            ]
        ],
        "LU" => [
            'name' => 'Luxembourg',
            'native' => 'Luxembourg',
            'postalcode' => [
                'max' => 4,
                'regex' => '^[1-9]{1}[0-9]{3}$'
            ],
            'vat' => [
                'max' => 10,
                'regex' => '^(LU)?[0-9]{8}$',
                'format' => 'LU01234567'
            ]
        ],
        "NL" => [
            'name' => 'The Netherlands',
            'native' => 'Nederland',
            'postalcode' => [
                'max' => 8,
                'regex' => '^[1-9][0-9]{3}[ ]?([A-RT-Za-rt-z][A-Za-z]|[sS][BCbcE-Re-rT-Zt-z])$'
            ],
            'vat' => [
                'max' => 14,
                'regex' => '^(NL)?[0-9]{9}B[0-9]{2}$',
                'format' => 'NL001234567B01'
            ]
        ],
        "AT" => [
            'name' => 'Austria',
            'native' => 'Österreich',
            "postalcode" => [
                "max" => 4,
                "regex" => '^[1-9]{1}[0-9]{3}$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(AT)?U[0-9]{8}$',
                'format' => 'ATU12345678'
            ]
        ],
        "BG" => [
            'name' => 'Bulgaria',
            'native' => 'българия',
            "postalcode" => [
                "max" => 4,
                "regex" => '^[1-9]{1}[0-9]{3}$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(BG)?[0-9]{9,10}$',
                'format' => 'BG12345678'
            ]
        ],
        "CY" => [
            'name' => 'Cyprus',
            'native' => 'Κύπρος',
            "postalcode" => [
                "max" => 4,
                "regex" => '^(\d{4})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(CY)?[0-9]{8}L$',
                'format' => 'CY12345678X'
            ]
        ],
        "CZ" => [
            'name' => 'Czech Republic',
            'native' => 'Česká republika',
            "postalcode" => [
                "max" => 6,
                "regex" => '^(\d{3}[ ]\d{2})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(CZ)(\d{8,10})?$',
                'format' => 'CZ1234567890'
            ]
        ],
        "DE" => [
            'name' => 'Germany',
            'native' => 'Deutschland',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(DE)([1-9]\d{8})$',
                'format' => 'DE123456789'
            ]
        ],
        "DK" => [
            'name' => 'Denmark',
            'native' => 'Danmark',
            "postalcode" => [
                "max" => 4,
                "regex" => '^(\d{4})$'
            ],
            'vat' => [
                'max' => 10,
                'regex' => '^(DK)(\d{8})?$',
                'format' => 'DK12345678'
            ]
        ],
        "EE" => [
            'name' => 'Estonia',
            'native' => 'Eesti',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 10,
                'regex' => '^(EE)?[0-9]{9}$',
                'format' => 'EE123456789'
            ]
        ],
        "ES" => [
            'name' => 'Spain',
            'native' => 'España',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(ES)?[0-9A-Z][0-9]{7}[0-9A-Z]$',
                'format' => 'ESX1234567X'
            ]
        ],
        "FI" => [
            'name' => 'Finland',
            'native' => 'Suomi',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 10,
                'regex' => '^(FI)?[0-9]{8}$',
                'format' => 'FI12345678'
            ]
        ],
        "FR" => [
            'name' => 'France',
            'native' => 'La France',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 13,
                'regex' => '^(FR)([0-9A-Z]{2}[0-9]{9})$',
                'format' => 'FRXX123456789'
            ]
        ],
        "GR" => [
            'name' => 'Greece',
            'native' => 'Ελλάδα',
            "postalcode" => [
                "max" => 6,
                "regex" => '^(\d{3}[ ]?\d{2})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(EL|GR)?[0-9]{9}$',
                'format' => 'EL123456789'
            ]
        ],
        "HR" => [
            'name' => 'Croatia',
            'native' => 'Hrvatska',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(HR)?[0-9]{11}$',
                'format' => 'HR12345678901'
            ]
        ],
        "HU" => [
            'name' => 'Hungary',
            'native' => 'Magyarország',
            "postalcode" => [
                "max" => 4,
                "regex" => '^(\d{4})$'
            ],
            'vat' => [
                'max' => 10,
                'regex' => '^(HU)?[0-9]{8}$',
                'format' => 'HU12345678'
            ]
        ],
        "IE" => [
            'name' => 'Ireland',
            'native' => 'Éire',
            "postalcode" => [
                "max" => 8,
                "regex" => '(?:^[AC-FHKNPRTV-Y][0-9]{2}|D6W)[ -]?[0-9AC-FHKNPRTV-Y]{4}$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(IE)([0-9A-Z\*\+]{7}[A-Z]{1,2})$',
                'format' => 'IE1234567FA'
            ]
        ],
        "IT" => [
            'name' => 'Italy',
            'native' => 'Italia',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(IT)(\d{11})$',
                'format' => 'IT12345678901'
            ]
        ],
        "LV" => [
            'name' => 'Latvia',
            'native' => 'Latvija',
            "postalcode" => [
                "max" => 4,
                "regex" => '^(\d{4})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(LV)?[0-9]{11}$',
                'format' => 'LV12345678901'
            ]
        ],
        "LT" => [
            'name' => 'Lithuania',
            'native' => 'Lietuva',
            "postalcode" => [
                "max" => 5,
                "regex" => '^(\d{5})$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(LT)?([\d]{9}|[\d]{12})$',
                'format' => 'LT123456789012'
            ]
        ],
        "MT" => [
            'name' => 'Malta',
            'native' => 'Malta',
            "postalcode" => [
                "max" => 8,
                "regex" => '^[A-Z]{3}[ ]?\d{2,4}$'
            ],
            'vat' => [
                'max' => 11,
                'regex' => '^(MT)?[\d]{8}$',
                'format' => 'MT12345678'
            ]
        ],
        "NO" => [
            'name' => 'Norway',
            'native' => 'Norge',
            "postalcode" => [
                "max" => 4,
                "regex" => '^(\d{4})$'
            ],
            'vat' => [
                'max' => 12,
                'regex' => '^(NO)(\d{9})$',
                'format' => 'NO123456789'
            ]
        ],
    ],

    'countries' => [
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "BQ" => "British Antarctic Territory",
        "IO" => "British Indian Ocean Territory",
        "VG" => "British Virgin Islands",
        "BN" => "Brunei",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CT" => "Canton and Enderbury Islands",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos [Keeling] Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo - Brazzaville",
        "CD" => "Congo - Kinshasa",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "CI" => "Côte d’Ivoire",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "NQ" => "Dronning Maud Land",
        "DD" => "East Germany",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "FQ" => "French Southern and Antarctic Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GG" => "Guernsey",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and McDonald Islands",
        "HN" => "Honduras",
        "HK" => "Hong Kong SAR China",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IM" => "Isle of Man",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JE" => "Jersey",
        "JT" => "Johnston Island",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Laos",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macau SAR China",
        "MK" => "Macedonia",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "FX" => "Metropolitan France",
        "MX" => "Mexico",
        "FM" => "Micronesia",
        "MI" => "Midway Islands",
        "MD" => "Moldova",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "ME" => "Montenegro",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar [Burma]",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NT" => "Neutral Zone",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "KP" => "North Korea",
        "VD" => "North Vietnam",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PC" => "Pacific Islands Trust Territory",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territories",
        "PA" => "Panama",
        "PZ" => "Panama Canal Zone",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "YD" => "People's Democratic Republic of Yemen",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn Islands",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RO" => "Romania",
        "RU" => "Russia",
        "RW" => "Rwanda",
        "RE" => "Réunion",
        "BL" => "Saint Barthélemy",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "MF" => "Saint Martin",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "RS" => "Serbia",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "KR" => "South Korea",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syria",
        "ST" => "São Tomé and Príncipe",
        "TW" => "Taiwan",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UM" => "U.S. Minor Outlying Islands",
        "PU" => "U.S. Miscellaneous Pacific Islands",
        "VI" => "U.S. Virgin Islands",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "SU" => "Union of Soviet Socialist Republics",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "ZZ" => "Unknown or Invalid Region",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VA" => "Vatican City",
        "VE" => "Venezuela",
        "VN" => "Vietnam",
        "WK" => "Wake Island",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe",
        "AX" => "Åland Islands",
    ]
];