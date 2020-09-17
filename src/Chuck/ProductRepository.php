<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck;

use Chuckbe\ChuckcmsModuleEcommerce\Chuck\AttributeRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CollectionRepository;
use Chuckbe\Chuckcms\Models\Repeater;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Collection;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Brand;
use Chuckbe\ChuckcmsModuleEcommerce\Models\Product;
use ChuckSite;
use ChuckEcommerce;
use Illuminate\Http\Request;
use Str;

class ProductRepository
{
	private $attributeRepository;
	private $repeater;
    private $product;
    private $collection;
    private $brand;

	public function __construct(
        AttributeRepository $attributeRepository, 
        CollectionRepository $collectionRepository, 
        Repeater $repeater, 
        Product $product, 
        Collection $collection, 
        Brand $brand)
    {
        $this->attributeRepository = $attributeRepository;
        $this->collectionRepository = $collectionRepository;
        $this->repeater = $repeater;
        $this->product = $product;
        $this->collection = $collection;
        $this->brand = $brand;
    }

    /**
     * Get all the products
     *
     * @var $id = null
     **/
    public function get($id = null)
    {
        if ( !is_null($id) ) {
            return $this->product->where('id', $id)->first();
        }
        return $this->product->get();
    }

    /**
     * Search all the products
     *
     * @var string $string
     **/
    public function search(string $string)
    {
        return $this->product
                    ->where('json->is_displayed', true)
                    ->where(function ($query) use ($string) {
                        $query->where('json->cody->sku', $string)
                            ->orWhere('json->cody->upc', $string)
                            ->orWhere('json->cody->ean', $string)
                            ->orWhereRaw('LOWER(json_unquote(json_extract(`json`, '.'\'$."title"."'.(string)app()->getLocale().'"'.'\'))) LIKE "%'.strtolower($string).'%"')
                            ->orWhereRaw('LOWER(json_unquote(json_extract(`json`, '.'\'$."page_title"."'.(string)app()->getLocale().'"'.'\'))) LIKE "%'.strtolower($string).'%"')
                            ->orWhereRaw('LOWER(json_unquote(json_extract(`json`, '.'\'$."description"."short"."'.(string)app()->getLocale().'"'.'\'))) LIKE "%'.strtolower($string).'%"')
                            ->orWhereRaw('LOWER(json_unquote(json_extract(`json`, '.'\'$."description"."long"."'.(string)app()->getLocale().'"'.'\'))) LIKE "%'.strtolower($string).'%"');
                    })
                    ->get();
    }

    public function getFeatured()
    {
        return $this->product->get();
    }

    public function forCollection($collection, $parent = null)
    {
        $query = $this->collection->where('json->name', $collection);
        
        if (!is_null($parent)) {
            $query = $query->where('json->parent', $parent);
        }
        
        $collection = $query->first();

        if ($collection == null) {
            return array();
        }

        return $this->product
                    ->where('json->collection', ''.$collection->id.'')
                    ->orWhereJsonContains('json->collection', ''.$collection->id.'')
                    ->get();
    }

    public function forBrand($brand)
    {
        $query = $this->brand->where('json->name', $brand);
        
        $brand = $query->first();

        if ($brand == null) {
            return array();
        }

        return $this->product->where('json->brand', ''.$brand->id.'')->get();
    }

    public function sku($sku)
    {
        if(is_array($sku)) {
            $query = $this->repeater->where('slug', config('chuckcms-module-ecommerce.products.slug'));
            foreach($sku as $sku_single) {
                $query->orWhereJsonContains('json', $sku_single);
            }
            return $query->get(); 
        }
        return $this->repeater->whereRaw('json LIKE "%'.$sku.'%"', [20000])->first();
    }

    public function save(Request $values)
    {
    	$input = [];
        $template = ChuckEcommerce::getTemplate();

    	$input['slug'] = config('chuckcms-module-ecommerce.products.slug');
        $input['url'] = config('chuckcms-module-ecommerce.products.url').Str::slug($values->get('slug'), '-');
        $input['page'] = $template->hintpath . '::templates.' . $template->slug . '.products.detail';

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
        if( count($values->get('attributes')) > 0 ) {
            foreach ($values->get('attributes') as $attributeId) {
            	$attribute = $this->attributeRepository->getById($attributeId);
            	$selectedOptions = $values->get('attribute')[$attribute->id];
            	
                if(count($selectedOptions) > 0) {
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
            }
        }
        $json['attributes'] = $attributes;


        $combinations = [];
        $totalQuantity = 0;

        if( $values['combination_slugs'][0] !== null && count($values['combination_slugs']) > 0 && count($values->get('attributes')) > 0 ) {
            if ( $values->get('attribute') !== null && count(array_values($values->get('attribute'))[0]) > 0 ) {
                $q = 0;
                foreach ($values->get('combination_slugs') as $combinationKey) {
                	$combinations[$combinationKey] = [];
                	$combinations[$combinationKey]['display_name'] = [];

                    if($q == 0) {
                        $combinations[$combinationKey]['is_default'] = true;
                    } else {
                        $combinations[$combinationKey]['is_default'] = false;
                    }

                	foreach ($langs as $langKey => $langValue) {
                		$combinations[$combinationKey]['display_name'][$langKey] = $values->get('combinations')[$combinationKey]['display_name'][$langKey];
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
        	        $combinations[$combinationKey]['price']['final'] = $values->get('price')['final'];//verkoopprijs incl korting, incl btw

                    $combinations[$combinationKey]['dimensions']['width'] = $values->get('width');
                    $combinations[$combinationKey]['dimensions']['height'] = $values->get('height');
                    $combinations[$combinationKey]['dimensions']['depth'] = $values->get('depth');
                    $combinations[$combinationKey]['dimensions']['weight'] = $values->get('weight');

        	        $combinations[$combinationKey]['quantity'] = (int)$values->get('combinations')[$combinationKey]['quantity'];
        	        $totalQuantity = $totalQuantity + (int)$values->get('combinations')[$combinationKey]['quantity'];

                    $q++;
                }    
            }
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

        if( $values['combination_slugs'][0] !== null && count($values['combination_slugs']) > 0 && count($values->get('attributes')) > 0) {
        	$json['quantity'] = $values->get('attribute') !== null ? $totalQuantity : (int)$values->get('quantity');
        } else {
        	$json['quantity'] = (int)$values->get('quantity');
        }
    	

    	foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['title'][$langKey] = $values->get('title')[$langKey];
            $json['page_title'][$langKey] = $values->get('title')[$langKey];

            $json['description']['short'][$langKey] = $values->get('description')['short'][$langKey];
            $json['description']['long'][$langKey] = $values->get('description')['long'][$langKey];
            
            $json['meta']['title'][$langKey] = $values->get('meta_title')[$langKey];
            $json['meta']['description'][$langKey] = $values->get('meta_description')[$langKey];
            $json['meta']['keywords'][$langKey] = $values->get('meta_keywords')[$langKey];
        }

        $json['dimensions']['width'] = $values->get('width');
        $json['dimensions']['height'] = $values->get('height');
        $json['dimensions']['depth'] = $values->get('depth');
        $json['dimensions']['weight'] = $values->get('weight');

        $input['json'] = $json;

        $product = $this->repeater->create($input);

        return $product;
    }

    public function update(Request $values)
    {
    	$input = [];
        $template = ChuckEcommerce::getTemplate();

        $product = $this->repeater->where('id', $values->get('product_id'))->first();

    	$input['slug'] = config('chuckcms-module-ecommerce.products.slug');
        $input['url'] = config('chuckcms-module-ecommerce.products.url').Str::slug($values->get('slug'), '-');
        $input['page'] = $template->hintpath . '::templates.' . $template->slug . '.products.detail';

    	$json = [];
    	$json['code']['sku'] = $product->json['code']['sku'];
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
        if( is_array($values->get('attributes')) ) {
            foreach ($values->get('attributes') as $attributeId) {
            	$attribute = $this->attributeRepository->getById($attributeId);
            	$selectedOptions = $values->get('attribute')[$attribute->id];

                if(count($selectedOptions) > 0) {
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
            }
        }
        $json['attributes'] = $attributes;


        $combinations = [];
        $totalQuantity = 0;

        if( $values['combination_slugs'][0] !== null && count($values['combination_slugs']) > 0 && count($values->get('attributes')) > 0 ) {
            if ( $values->get('attribute') !== null && count(array_values($values->get('attribute'))[0]) > 0 ) {
                $q = 0;
                foreach ($values->get('combination_slugs') as $combinationKey) {
                	$combinations[$combinationKey] = [];
                	$combinations[$combinationKey]['display_name'] = [];
                    
                    if($q == 0) {
                        $combinations[$combinationKey]['is_default'] = true;
                    } else {
                        $combinations[$combinationKey]['is_default'] = false;
                    }
                    
                	foreach ($langs as $langKey => $langValue) {
                		$combinations[$combinationKey]['display_name'][$langKey] = $values->get('combinations')[$combinationKey]['display_name'][$langKey];
                	}

                    if(array_key_exists($combinationKey, $product->json['combinations'])) {
                        $combinations[$combinationKey]['code']['sku'] = $product->json['combinations'][$combinationKey]['code']['sku'];
                        $combinations[$combinationKey]['code']['upc'] = $product->json['combinations'][$combinationKey]['code']['upc'];
                        $combinations[$combinationKey]['code']['ean'] = $product->json['combinations'][$combinationKey]['code']['ean'];
                    } else {
                        $combinations[$combinationKey]['code']['sku'] = $this->generateSingleSku();
                        $combinations[$combinationKey]['code']['upc'] = null;
                        $combinations[$combinationKey]['code']['ean'] = null;
                    }

                	$combinations[$combinationKey]['price']['unit']['amount'] = $values->get('price')['unit']['amount'];
        	        $combinations[$combinationKey]['price']['unit']['type'] = $values->get('price')['unit']['type'];
        	        $combinations[$combinationKey]['price']['wholesale'] = $values->get('price')['wholesale'];//inkoopprijs
        	        $combinations[$combinationKey]['price']['sale'] = $values->get('combinations')[$combinationKey]['price']['sale'];//verkoopprijs excl btw
        	        $combinations[$combinationKey]['price']['discount'] = $values->get('combinations')[$combinationKey]['price']['discount'];//kortingsprijs
        	        $combinations[$combinationKey]['price']['vat']['amount'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.amount');
        	        $combinations[$combinationKey]['price']['vat']['type'] = config('chuckcms-module-ecommerce.vat.'.$values->get('price')['vat'].'.type');
        	        $combinations[$combinationKey]['price']['final'] = $values->get('combinations')[$combinationKey]['price']['final'];//verkoopprijs incl korting, incl btw

                    $combinations[$combinationKey]['dimensions']['width'] = $values->get('combinations')[$combinationKey]['dimensions']['width'];
                    $combinations[$combinationKey]['dimensions']['height'] = $values->get('combinations')[$combinationKey]['dimensions']['height'];
                    $combinations[$combinationKey]['dimensions']['depth'] = $values->get('combinations')[$combinationKey]['dimensions']['depth'];
                    $combinations[$combinationKey]['dimensions']['weight'] = $values->get('combinations')[$combinationKey]['dimensions']['weight'];

        	        $combinations[$combinationKey]['quantity'] = (int)$values->get('combinations')[$combinationKey]['quantity'];
        	        $totalQuantity = $totalQuantity + (int)$values->get('combinations')[$combinationKey]['quantity'];

                    $q++;
                }
            }
        
        }
        $json['combinations'] = $combinations;

        $options = [];
        if(is_array($values->option_key)) {
            $countOptions = count($values->option_key);
            for ($i=0; $i < $countOptions; $i++) { 
                $options[$values->option_key[$i]]['value'] = $values->option_value[$i];
            }
        }
        $json['options'] = $options;


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

        if( $values['combination_slugs'][0] !== null && count($values['combination_slugs']) > 0 && count($values->get('attributes')) > 0) {
            $json['quantity'] = $values->get('attribute') !== null ? $totalQuantity : (int)$values->get('quantity');
        } else {
            $json['quantity'] = (int)$values->get('quantity');
        }
    	

    	foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue){
            $json['title'][$langKey] = $values->get('title')[$langKey];
            $json['page_title'][$langKey] = $values->get('title')[$langKey];

            $json['description']['short'][$langKey] = $values->get('description')['short'][$langKey];
            $json['description']['long'][$langKey] = $values->get('description')['long'][$langKey];
            
            $json['meta']['title'][$langKey] = $values->get('meta_title')[$langKey];
            $json['meta']['description'][$langKey] = $values->get('meta_description')[$langKey];
            $json['meta']['keywords'][$langKey] = $values->get('meta_keywords')[$langKey];
        }

        $json['dimensions']['width'] = $values->get('width');
        $json['dimensions']['height'] = $values->get('height');
        $json['dimensions']['depth'] = $values->get('depth');
        $json['dimensions']['weight'] = $values->get('weight');

        $input['json'] = $json;

        $product->update($input); // change to update

        return $product;
    }

    public function generateSingleSku()
    {
        $uid = rand(1000000, 9999999);
        $uids = $this->repeater->where('slug', config('chuckcms-module-ecommerce.products.slug'))->whereRaw('json LIKE "%'.$uid.'%"', [20000])->get();
        if (count($uids) > 0) {
            $this->generateSingleSku();
        } else {
            return $uid;
        }
    }

    public function getAttributes(Repeater $product)
    {
        return $product->json['attributes'];
    }

    public function getCollection(Repeater $product)
    {
        return $this->collectionRepository->getById($product->json['collection']); 
    }

    public function hasSKU(Repeater $product, $sku) :bool
    {
        if($product->json['code']['sku'] == $sku) {
            return true;
        }

        foreach($product->json['combinations'] as $key => $combination) {
            if($combination['code']['sku'] == $sku) {
                return true;
            }
        }

        return false;
    }

    public function isCombination(Repeater $product, $sku) :bool
    {
        if (count($product->json['combinations']) > 0 ) {
            return $product->json['code']['sku'] !== $sku;
        } else {
            return false;
        }
    }

    public function defaultCombination(Repeater $product)
    {
        foreach ( $product->json['combinations'] as $combination) {
            if( (bool)$combination['is_default'] == true ) {
                return $combination;
            }
        }
        return array();
    }

    public function combinationBySKU($sku)
    {
        $product = $this->sku($sku);
        
        if(is_null($product)) {
            return array();
        }

        foreach ( $product->json['combinations'] as $combination) {
            if( $combination['code']['sku'] == $sku ) {
                return $combination;
            }
        }
        
        return array();
    }

    public function combinationForSKU(Repeater $product, $sku)
    {   
        foreach ( $product->json['combinations'] as $combination) {
            if( $combination['code']['sku'] == $sku ) {
                return $combination;
            }
        }
        
        return array();
    }

    public function combinationKeyForSKU(Repeater $product, $sku)
    {   
        foreach ( $product->json['combinations'] as $key => $combination) {
            if( $combination['code']['sku'] == $sku ) {
                return $key;
            }
        }
        
        return null;
    }

    public function defaultCombinationKey(Repeater $product)
    {
        foreach ( $product->json['combinations'] as $key => $combination) {
            if( (bool)$combination['is_default'] == true ) {
                return $key;
            }
        }
        return '';
    }

    public function defaultSKU(Repeater $product)
    {
        if( count($product->json['combinations']) == 0 ) {
            return $product->json['code']['sku'];
        }
        foreach ( $product->json['combinations'] as $key => $combination) {
            if( (bool)$combination['is_default'] == true ) {
                return $combination['code']['sku'];
            }
        }
        return '';
    }

    public function getCombination(Repeater $product, $attributes)
    {
        foreach ( $product->json['combinations'] as $combinationKey => $combination) {
            $combinationKeySplit = explode('__', $combinationKey);
            if( !array_diff($attributes, $combinationKeySplit) ) {
                return $combination;
            }
        }
        return array();
    }

    public function title(Repeater $product)
    {
        return $product->json['title'][(string)app()->getLocale()];
    }

    public function brand(Repeater $product)
    {
        $brand = $this->brand->where('id', (int)$product->json['brand'])->first();
        return is_null($brand) ? null : $brand->name;
    }

    public function images(Repeater $product)
    {
        return $product->json['images'];
    }

    public function inStock(Repeater $product)
    {
        if ( count($product->json['combinations']) == 0 ) {
            return $product->json['quantity'] > 0 ? true : false;
        }
        return $this->defaultCombination($product)['quantity'] > 0 ? true : false;
    }

    public function featuredImage(Repeater $product)
    {
        return ChuckSite::getSite('domain') . array_values($product->json['images'])[0]['url'];
    }

    public function featuredImageBySKU($sku)
    {
        return ChuckSite::getSite('domain') . array_values($this->sku($sku)->json['images'])[0]['url'];
    }

    public function getFullUrlBySKU($sku)
    {
        return ChuckSite::getSite('domain') . '/' . $this->sku($sku)->url;
    }

    public function getImageHeight(array $image)
    {
        return getimagesize(ChuckSite::getSite('domain') . $image['url'])[1];
    }

    public function getImageWidth(array $image)
    {
        return getimagesize(ChuckSite::getSite('domain') . $image['url'])[0];
    }

    public function fullUrl(Repeater $product)
    {
        return ChuckSite::getSite('domain') . '/' . $product->url;
    }

    public function hasDiscount(Repeater $product)
    {
        if ( count($product->json['combinations']) == 0 ) {
            return (float)$product->json['price']['discount'] == 0.0 ? false : ((float)$product->json['price']['discount'] < (float)$product->json['price']['final'] ? true : false); 
        }
        foreach ( $product->json['combinations'] as $combination) {
            if( (float)$combination['price']['discount'] !== 0.0 && (float)$combination['price']['discount'] < (float)$combination['price']['final'] && $combination['is_default'] ) {
                return true;
            }
        }
        return false;
    }

    public function priceObject(Repeater $product, $sku) 
    {
        if($product->json['code']['sku'] == $sku) {
            return $product->json['price'];
        }

        $combination = $this->combinationForSKU($product, $sku);

        return $combination['price'];
    }

    public function lowestPrice(Repeater $product)
    {
        if ( count($product->json['combinations']) == 0 ) {
            $price = (float)$product->json['price']['discount'] == 0.0 ? $product->json['price']['final'] : ((float)$product->json['price']['discount'] < (float)$product->json['price']['final'] ? $product->json['price']['discount'] : $product->json['price']['final']); 
            return ChuckEcommerce::formatPrice($price);
        }

        $prices = [];
        foreach ( $product->json['combinations'] as $combination) {
            if( (float)$combination['price']['discount'] !== 0.0 && (float)$combination['price']['discount'] < (float)$combination['price']['final'] ) {
                $prices[] = (float)$combination['price']['discount'];
                continue;
            }
            $prices[] = (float)$combination['price']['final'];
        }

        return ChuckEcommerce::formatPrice(min($prices));
    }

    public function highestPrice(Repeater $product)
    {
        if ( count($product->json['combinations']) == 0 ) {
            $price = (float)$product->json['price']['discount'] == 0.0 ? $product->json['price']['final'] : ((float)$product->json['price']['discount'] < (float)$product->json['price']['final'] ? $product->json['price']['final'] : $product->json['price']['discount']); 
            return ChuckEcommerce::formatPrice($price);
        }

        $prices = [];
        foreach ( $product->json['combinations'] as $combination) {
            if( (float)$combination['price']['discount'] !== 0.0 && (float)$combination['price']['discount'] < (float)$combination['price']['final'] ) {
                $prices[] = (float)$combination['price']['final'];
                continue;
            }
            $prices[] = (float)$combination['price']['final'];
        }

        return ChuckEcommerce::formatPrice(max($prices));
    }

    public function priceRaw(Repeater $product, $sku)
    {
        if($product->json['code']['sku'] == $sku) {
            if((float)$product->json['price']['discount'] !== 0.0) {
                return (float)$product->json['price']['discount'];
            }
            return (float)$product->json['price']['final'];
        }

        foreach ( $product->json['combinations'] as $combination) {
            
            if($combination['code']['sku'] == $sku) {
                if( (float)$combination['price']['discount'] !== 0.0) {
                    return (float)$combination['price']['discount'];
                } else {
                    return (float)$combination['price']['final'];
                }
            }
        }

        return 0.00;
    }

    public function priceNoTaxRaw(Repeater $product, $sku)
    {
        if($product->json['code']['sku'] == $sku) {
            if((float)$product->json['price']['discount'] !== 0.0) {
                return $this->priceWithoutTax((float)$product->json['price']['discount'], $product->json['price']['vat']['amount']);
            }
            return (float)$product->json['price']['sale'];
        }

        foreach ( $product->json['combinations'] as $combination) {
            
            if($combination['code']['sku'] == $sku) {
                if( (float)$combination['price']['discount'] !== 0.0) {
                    return $this->priceWithoutTax((float)$combination['price']['discount'], $combination['price']['vat']['amount']);
                } else {
                    return (float)$combination['price']['sale'];
                }
            }
        }

        return 0.00;
    }

    private function priceWithoutTax($price, $tax)
    {
        $newprice = $price / ((100 + $tax) / 100);
        return round($newprice, 2);
    }

    public function quantity(Repeater $product, $sku)
    {
        if($product->json['code']['sku'] == $sku) {
            return (int)$product->json['quantity'];
        }
        foreach ( $product->json['combinations'] as $combination) {
            if($combination['code']['sku'] == $sku) {
                return (int)$combination['quantity'];
            }
        }

        return 0;
    }

    public function taxRate(Repeater $product)
    {
        return $product->json['price']['vat']['amount'];
    }

    public function taxRateBySKU($sku)
    {
        return $this->combinationBySKU($sku)['price']['vat']['amount'];
    }

    public function taxRateForSKU(Repeater $product, $sku)
    {
        if($product->json['code']['sku'] == $sku) {
            return $product->json['price']['vat']['amount'];
        }

        return $this->combinationForSKU($product, $sku)['price']['vat']['amount'];
    }

    public function weightForSKU($sku)
    {
        $product = $this->sku($sku);
        if($this->isCombination($product, $sku)) {
            $combination = $this->combinationForSKU($product, $sku);
            return array_key_exists('dimensions', $combination) ? (float)$combination['dimensions']['weight'] : 0.00;
        } 

        return array_key_exists('dimensions', $product->json) ? (float)$product->json['dimensions']['weight'] : 0.00;
    }

    public function getOptions(Repeater $product, $sku, $given_options)
    {
        if($product->json['code']['sku'] == $sku) {
            return array();
        }
        foreach ( $product->json['combinations'] as $combinationKey => $combination) {
            if($combination['code']['sku'] == $sku) {
                $key = $combinationKey;
            }
        }

        $combination_attribute_keys = explode('__', $key);
        $options = [];
        foreach($product->json['attributes'] as $attribute) {
            foreach($attribute['values'] as $attributeKey => $attributeValue) {
                if(in_array($attributeKey, $combination_attribute_keys)) {
                    $options[$attribute['key']] = $attributeValue['display_name'][app()->getLocale()];
                }
            }

        }

        if ( array_key_exists('options', $product->json)) {
            if (is_array($given_options)) {
                foreach($given_options as $given_option) {
                    $optionKey = explode('%|%', $given_option)[0];
                    $optionValue = explode('%|%', $given_option)[1];
                    if ( array_key_exists($optionKey, $product->json['options'])) {
                        $options[Str::slug($optionKey, '_')] = $optionValue;
                    }
                }
            } elseif (is_a($given_options, 'Illuminate\Support\Collection')) {
                foreach($given_options as $optionKey => $optionValue) {
                    if ( array_key_exists($optionKey, $product->json['options'])) {
                        $options[Str::slug($optionKey, '_')] = $optionValue;
                    }
                }
            }
        }

        return $options;
    }

    public function getOptionsText(Repeater $product, $sku, $given_options = [])
    {
        if($product->json['code']['sku'] == $sku) {
            return null;
        }
        foreach ( $product->json['combinations'] as $combinationKey => $combination) {
            if($combination['code']['sku'] == $sku) {
                $key = $combinationKey;
            }
        }

        $combination_attribute_keys = explode('__', $key);
        $options = [];
        foreach($product->json['attributes'] as $attribute) {
            foreach($attribute['values'] as $attributeKey => $attributeValue) {
                if(in_array($attributeKey, $combination_attribute_keys)) {
                    $options[] = $attribute['key'].': '.$attributeValue['display_name'][app()->getLocale()];
                }
            }

        }

        if ( array_key_exists('options', $product->json)) {
            if (is_a($given_options, 'Illuminate\Support\Collection')) {
                foreach($given_options as $optionKey => $optionValue) {
                    foreach($product->json['options'] as $poKey => $poValue) {
                        if ($optionKey == Str::slug($poKey, '_')) {
                            $options[] = $poKey.': '.$optionValue;
                        }
                    }
                }
            }
        }

        return implode(', ', $options);
    }

}