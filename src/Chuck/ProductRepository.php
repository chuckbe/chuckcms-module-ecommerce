<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\Chuckcms\Models\Repeater;

class ProductRepository
{
	private $repeater;

	public function __construct(Repeater $repeater)
    {
        $this->repeater = $repeater;
    }

    /**
     * Get all the products
     *
     * @var string
     **/
    public function get()
    {
        return $this->repeater->where('slug', 'products')->get();
    }

    public function save($values)
    {
    	$slug = "products";
    	$url = "";
    	$page = config('chuckcms-module-ecommerce.pages.product_detail');

    	$json = [];
    	$json['code']['sku'] = $this->generateSingleSku();
        $json['code']['upc'] = $values->get('code.upc');
        $json['code']['ean'] = $values->get('code.ean');

        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);
        $json['is_download'] = ($values->get('is_download') == '1' ? true : false);

        $json['price']['unit']['amount'] = $values->get('price.unit.amount');
        $json['price']['unit']['type'] = $values->get('price.unit.type');
        $json['price']['wholesale'] = $values->get('price.wholesale');//inkoopprijs
        $json['price']['sale'] = $values->get('price.sale');//verkoopprijs excl btw
        $json['price']['discount'] = $values->get('price.discount');//kortingsprijs
        $json['price']['vat']['amount'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price.vat').'.amount');
        $json['price']['vat']['type'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price.vat').'.type');
        $json['price']['final'] = $values->get('price.final');//verkoopprijs incl korting, incl btw

        $json['collection'] = $values->get('collection');
        $json['brand'] = $values->get('brand');

        $attributes = [];
        //for ($i=0; $i < count($values->('attributes')); $i++) { 
        	# code...
        	# 
        	//color - green and pink
        	//size - small and medium and large
        //}
     //    {
	    //     "collection": "[collection_slug]",
	    //     "brand": "[brand_slug]",

	    //     "attributes":{
	    //     	"color":{
	    //     		"display_name": {
	    //     			"nl":"Kleur",
	    //     			"en":"Color"
	    //     		},
	    //     		"values": {
	    //     			"green": {
	    //     				"display_name": {
	    //     					"nl":"Groen",
	    //     					"en":"Green"
	    //     				},
	    //     				"value": "#EG7654"
	    //     			},
	    //     			"pink": {
	    //     				"display_name": {
	    //     					"nl":"Roze",
	    //     					"en":"Pink"
	    //     				},
	    //     				"value": "#897RE3"
	    //     			}
	    //     		}
	    //     	},
	    //     	"size":{
	    //     		"display_name": {
	    //     			"nl":"Maat",
	    //     			"en":"Size"
	    //     		},
	    //     		"values": {
	    //     			"s": {
	    //     				"display_name": {
	    //     					"nl":"Small",
	    //     					"en":"Small"
	    //     				},
	    //     				"value": null
	    //     			},
	    //     			"m": {
	    //     				"display_name": {
	    //     					"nl":"Medium",
	    //     					"en":"Medium"
	    //     				},
	    //     				"value": null
	    //     			},
	    //     			"l": {
	    //     				"display_name": {
	    //     					"nl":"Large",
	    //     					"en":"Large"
	    //     				},
	    //     				"value": null
	    //     			}
	    //     		}
	    //     	}
	    //     },
	    //     "combinations": {
	    //     	"greens":{
	    //     		"display_name":{
	    //     			"nl":"ProductTitle + [space] + Kleur Groen Maat Small",
	    //     			"en":"ProductTitle + [space] + Color Green Size Small"
	    //     		},
	    //     		"code":{
	    //     			"sku": null,//8 CHAR ALPHA NUMERIC STRING UNIQUE IDENTIFIER
	    //     			"upc": null,//12 DIGIT
	    //     			"ean": null//13 DIGIT NUMERIC UNIQUE IDENTIFIER
	    //     		},
	    //     		"price":{
	    //     			"unit": ,
	    //     			"wholesale": ,
	    //     			"sale": ,
	    //     			"discount": ,
	    //     			"final": 
	    //     		},
	    //     		"quantity":2
	    //     		//image ? 
	    //     		//weight impact
	    //     	},
	    //     	"greenm":{

	    //     	},
	    //     	"greenl":{

	    //     	},
	    //     	"pinks":{

	    //     	},
	    //     	"pinkm":{

	    //     	},
	    //     	"pinkl":{
	        		
	    //     	}
	    //     },
	    //     "images": {
	    //     	"image1": {
	    //     		"url": "https://url.com/image.png",
	    //     		"alt": {
	    //     			"nl": "alt tekst",
	    //     			"en": "alt text"
	    //     		},
	    //     		"position": 1,
	    //     		"is_featured": true
	    //     	},
	    //     	"image2": {
	    //     		"url": "https://url.com/image2.png",
	    //     		"alt": {
	    //     			"nl": "alt tekst",
	    //     			"en": "alt text"
	    //     		},
	    //     		"position": 3,
	    //     		"is_featured": false
	    //     	},
	    //     	"image3": {
	    //     		"url": "https://url.com/image3.png",
	    //     		"alt": {
	    //     			"nl": "alt tekst",
	    //     			"en": "alt text"
	    //     		},
	    //     		"position": 2,
	    //     		"is_featured": false
	    //     	}
	    //     }
    	// }
    	

        

        $json['attributes'] = $attributes;

    	$json['quantity'] = "";

        

    	foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['title'][$langKey] = $values->get('title')[$langKey];
            $json['page_title'][$langKey] = "";

            $json['description']['short'][$langKey] = "";
            $json['description']['long'][$langKey] = "";
            
            $json['meta']['title'][$langKey] = "";
            $json['meta']['description'][$langKey] = "";
            $json['meta']['keywords'][$langKey] = "";
        }

        
    }

    public function generateSingleSku()
    {
    	return 'uniquevalue';
    }

}