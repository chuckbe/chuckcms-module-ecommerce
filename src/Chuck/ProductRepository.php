<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\Chuckcms\Models\Repeater;
use ChuckSite;
use Illuminate\Http\Request;

class ProductRepository
{
	private $attributeRepository;
	private $repeater;

	public function __construct(AttributeRepository $attributeRepository, Repeater $repeater)
    {
        $this->attributeRepository = $attributeRepository;
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

    public function save(Request $values)
    {
    	$input = [];

    	$input['slug'] = config('chuckcms-module-ecommerce.products.slug');
        $input['url'] = config('chuckcms-module-ecommerce.products.url').str_slug($values->get('slug'), '-');
        $input['page'] = config('chuckcms-module-ecommerce.products.page');

    	$json = [];
    	$json['code']['sku'] = $this->generateSingleSku();
        $json['code']['upc'] = $values->get('code')['upc'];
        $json['code']['ean'] = $values->get('code')['ean'];

        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);
        $json['is_download'] = ($values->get('is_download') == '1' ? true : false);

        $json['price']['unit']['amount'] = $values->get('price')['unit']['amount'];
        $json['price']['unit']['type'] = $values->get('price')['unit']['type'];
        $json['price']['wholesale'] = $values->get('price')['wholesale'];//inkoopprijs
        $json['price']['sale'] = $values->get('price')['sale'];//verkoopprijs excl btw
        $json['price']['discount'] = $values->get('price')['discount'];//kortingsprijs
        $json['price']['vat']['amount'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.amount');
        $json['price']['vat']['type'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.type');
        $json['price']['final'] = $values->get('price')['final'];//verkoopprijs incl korting, incl btw

        $json['collection'] = $values->get('collection');
        $json['brand'] = $values->get('brand');

        $attributes = [];
        $langs = ChuckSite::getSupportedLocales();
        foreach ($values->get('attributes') as $attributeId) {
        	$attribute = $this->attributeRepository->getById($attributeId);
        	$selectedOptions = $values->get('attribute')[$attribute->id];
        	
        	$attributes[$attribute->id] = [];
        	$attributes[$attribute->id]['key'] = str_slug($attribute->json['name'], '_');
        	$attributes[$attribute->id]['display_name'] = [];
        	foreach ($langs as $langKey => $langValue) {
        		$attributes[$attribute->id]['display_name'][$langKey] = $attribute->json['display_name'][$langKey];
        	}
        	$attributes[$attribute->id]['values'] = [];

        	foreach ($selectedOptions as $attrOptionKey) {
        		$attributes[$attribute->id]['values'][$attrOptionKey] = [];
        		$attributes[$attribute->id]['values'][$attrOptionKey]['display_name'] = [];
        		foreach ($langs as $langKey => $langValue) {
        			$attributes[$attribute->id]['values'][$attrOptionKey]['display_name'][$langKey] = $attribute->json['values'][$attrOptionKey]['display_name'][$langKey];
        		}
        		$attributes[$attribute->id]['values'][$attrOptionKey]['value'] = $attribute->json['values'][$attrOptionKey]['value'];
        	}
        }
        $json['attributes'] = $attributes;


        $combinations = [];
        $totalQuantity = 0;

        foreach ($values->get('combination_slugs') as $combinationKey) {
        	$combinations[$combinationKey] = [];
        	$combinations[$combinationKey]['display_name'] = [];
        	foreach ($langs as $langKey => $langValue) {
        		$combinations[$combinationKey]['display_name'][$langKey] = $values->get('title')[$langKey] . ' ' . $values->get('combinations')[$combinationKey]['display_name'][$langKey];
        	}

        	$combinations[$combinationKey]['code']['sku'] = $this->generateSingleSku();
        	$combinations[$combinationKey]['code']['upc'] = null;
        	$combinations[$combinationKey]['code']['ean'] = null;

        	$combinations[$combinationKey]['price']['unit']['amount'] = $values->get('price')['unit']['amount'];
	        $combinations[$combinationKey]['price']['unit']['type'] = $values->get('price')['unit']['type'];
	        $combinations[$combinationKey]['price']['wholesale'] = $values->get('price')['wholesale'];//inkoopprijs
	        $combinations[$combinationKey]['price']['sale'] = $values->get('price')['sale'];//verkoopprijs excl btw
	        $combinations[$combinationKey]['price']['discount'] = $values->get('price')['discount'];//kortingsprijs
	        $combinations[$combinationKey]['price']['vat']['amount'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.amount');
	        $combinations[$combinationKey]['price']['vat']['type'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.type');
	        $combinations[$combinationKey]['price']['final'] = $values->get('price.final');//verkoopprijs incl korting, incl btw

	        $combinations[$combinationKey]['quantity'] = (int)$values->get('combinations')[$combinationKey]['quantity'];
	        $totalQuantity = $totalQuantity + (int)$values->get('combinations')[$combinationKey]['quantity'];

        }
        $json['combinations'] = $combinations;


        $json['images']['image0']['url'] = $values->get('featured_image');
        $json['images']['image0']['alt'] = str_slug($values->get('slug'), '-');
        $json['images']['image0']['position'] = 0;
        $json['images']['image0']['is_featured'] = true;

        $i = 1;
        foreach($values->get('image') as $image){
        	$json['images']['image'.$i]['url'] = $image;
        	$json['images']['image'.$i]['alt'] = str_slug($values->get('slug'), '-');
        	$json['images']['image'.$i]['position'] = $i;
        	$json['images']['image'.$i]['is_featured'] = false;

        	$i++;
        }

        if( count($values->get('combination_slugs')) > 0) {
        	$json['quantity'] = $totalQuantity;
        } else {
        	$json['quantity'] = (int)$values->get('quantity');
        }
    	

    	foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['title'][$langKey] = $values->get('title')[$langKey];
            $json['page_title'][$langKey] = $values->get('title')[$langKey];

            $json['description']['short'][$langKey] = $values->get('discription.short')[$langKey];
            $json['description']['long'][$langKey] = $values->get('discription.long')[$langKey];
            
            $json['meta']['title'][$langKey] = $values->get('meta_title')[$langKey];
            $json['meta']['description'][$langKey] = $values->get('meta_description')[$langKey];
            $json['meta']['keywords'][$langKey] = $values->get('meta_keywords')[$langKey];
        }

        $input['json'] = $json;

        $product = $this->repeater->create($input);

        return $product;
    }

    public function update(Request $values)
    {
    	$input = [];

    	$input['slug'] = config('chuckcms-module-ecommerce.products.slug');
        $input['url'] = config('chuckcms-module-ecommerce.products.url').str_slug($values->get('slug'), '-');
        $input['page'] = config('chuckcms-module-ecommerce.products.page');

    	$json = [];
    	$json['code']['sku'] = $this->generateSingleSku();
        $json['code']['upc'] = $values->get('code')['upc'];
        $json['code']['ean'] = $values->get('code')['ean'];

        $json['is_displayed'] = ($values->get('is_displayed') == '1' ? true : false);
        $json['is_buyable'] = ($values->get('is_buyable') == '1' ? true : false);
        $json['is_download'] = ($values->get('is_download') == '1' ? true : false);

        $json['price']['unit']['amount'] = $values->get('price')['unit']['amount'];
        $json['price']['unit']['type'] = $values->get('price')['unit']['type'];
        $json['price']['wholesale'] = $values->get('price')['wholesale'];//inkoopprijs
        $json['price']['sale'] = $values->get('price')['sale'];//verkoopprijs excl btw
        $json['price']['discount'] = $values->get('price')['discount'];//kortingsprijs
        $json['price']['vat']['amount'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.amount');
        $json['price']['vat']['type'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.type');
        $json['price']['final'] = $values->get('price')['final'];//verkoopprijs incl korting, incl btw

        $json['collection'] = $values->get('collection');
        $json['brand'] = $values->get('brand');

        $attributes = [];
        $langs = ChuckSite::getSupportedLocales();
        foreach ($values->get('attributes') as $attributeId) {
        	$attribute = $this->attributeRepository->getById($attributeId);
        	$selectedOptions = $values->get('attribute')[$attribute->id];
        	
        	$attributes[$attribute->id] = [];
        	$attributes[$attribute->id]['key'] = str_slug($attribute->json['name'], '_');
        	$attributes[$attribute->id]['display_name'] = [];
        	foreach ($langs as $langKey => $langValue) {
        		$attributes[$attribute->id]['display_name'][$langKey] = $attribute->json['display_name'][$langKey];
        	}
        	$attributes[$attribute->id]['values'] = [];

        	foreach ($selectedOptions as $attrOptionKey) {
        		$attributes[$attribute->id]['values'][$attrOptionKey] = [];
        		$attributes[$attribute->id]['values'][$attrOptionKey]['display_name'] = [];
        		foreach ($langs as $langKey => $langValue) {
        			$attributes[$attribute->id]['values'][$attrOptionKey]['display_name'][$langKey] = $attribute->json['values'][$attrOptionKey]['display_name'][$langKey];
        		}
        		$attributes[$attribute->id]['values'][$attrOptionKey]['value'] = $attribute->json['values'][$attrOptionKey]['value'];
        	}
        }
        $json['attributes'] = $attributes;


        $combinations = [];
        $totalQuantity = 0;

        foreach ($values->get('combination_slugs') as $combinationKey) {
        	$combinations[$combinationKey] = [];
        	$combinations[$combinationKey]['display_name'] = [];
        	foreach ($langs as $langKey => $langValue) {
        		$combinations[$combinationKey]['display_name'][$langKey] = $values->get('title')[$langKey] . ' ' . $values->get('combinations')[$combinationKey]['display_name'][$langKey];
        	}

        	$combinations[$combinationKey]['code']['sku'] = $this->generateSingleSku();
        	$combinations[$combinationKey]['code']['upc'] = null;
        	$combinations[$combinationKey]['code']['ean'] = null;

        	$combinations[$combinationKey]['price']['unit']['amount'] = $values->get('price')['unit']['amount'];
	        $combinations[$combinationKey]['price']['unit']['type'] = $values->get('price')['unit']['type'];
	        $combinations[$combinationKey]['price']['wholesale'] = $values->get('price')['wholesale'];//inkoopprijs
	        $combinations[$combinationKey]['price']['sale'] = $values->get('price')['sale'];//verkoopprijs excl btw
	        $combinations[$combinationKey]['price']['discount'] = $values->get('price')['discount'];//kortingsprijs
	        $combinations[$combinationKey]['price']['vat']['amount'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.amount');
	        $combinations[$combinationKey]['price']['vat']['type'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.type');
	        $combinations[$combinationKey]['price']['final'] = $values->get('price.final');//verkoopprijs incl korting, incl btw

	        $combinations[$combinationKey]['quantity'] = (int)$values->get('combinations')[$combinationKey]['quantity'];
	        $totalQuantity = $totalQuantity + (int)$values->get('combinations')[$combinationKey]['quantity'];

        }
        $json['combinations'] = $combinations;


        $json['images']['image0']['url'] = $values->get('featured_image');
        $json['images']['image0']['alt'] = str_slug($values->get('slug'), '-');
        $json['images']['image0']['position'] = 0;
        $json['images']['image0']['is_featured'] = true;

        $i = 1;
        foreach($values->get('image') as $image){
        	$json['images']['image'.$i]['url'] = $image;
        	$json['images']['image'.$i]['alt'] = str_slug($values->get('slug'), '-');
        	$json['images']['image'.$i]['position'] = $i;
        	$json['images']['image'.$i]['is_featured'] = false;

        	$i++;
        }

        if( count($values->get('combination_slugs')) > 0) {
        	$json['quantity'] = $totalQuantity;
        } else {
        	$json['quantity'] = (int)$values->get('quantity');
        }
    	

    	foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['title'][$langKey] = $values->get('title')[$langKey];
            $json['page_title'][$langKey] = $values->get('title')[$langKey];

            $json['description']['short'][$langKey] = $values->get('discription.short')[$langKey];
            $json['description']['long'][$langKey] = $values->get('discription.long')[$langKey];
            
            $json['meta']['title'][$langKey] = $values->get('meta_title')[$langKey];
            $json['meta']['description'][$langKey] = $values->get('meta_description')[$langKey];
            $json['meta']['keywords'][$langKey] = $values->get('meta_keywords')[$langKey];
        }

        $input['json'] = $json;

        $product = $this->repeater->create($input); // change to update

        return $product;
    }

    public function generateSingleSku()
    {
    	return 'uniquevalue';
    }

}