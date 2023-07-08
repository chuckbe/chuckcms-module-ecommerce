<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Exports;

use ChuckEcommerce;
use ChuckProduct;
use ChuckRepeater;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class FacebookExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'availability',
            'condition',
            'price',
            'link',
            'image_link',
            'brand',
            'fb_product_category',
            'google_product_category'
        ];
    }

    public function collection()
    {
        $custom_array = [];
        $product_category_collection = ChuckRepeater::for('facebook_product_category');
        
        foreach(ChuckProduct::all() as $product){
            $brand = ChuckRepeater::for('brands')->where('id', $product->json['brand'])->first();
            $fb_pc_id = ChuckRepeater::for('collections')->where('id', $product->json['collection'][0])->first()->json['fb_product_category'];
            $google_pc_id = ChuckRepeater::for('collections')->where('id', $product->json['collection'][0])->first()->json['google_product_category'];
            
            $fb_product_category = $product_category_collection->filter(function($item) use ($fb_pc_id) {
                return $item->json['category_id'] == $fb_pc_id;
            })->first()->json['category'];

            $google_product_category = $product_category_collection->filter(function($item) use ($google_pc_id) {
                return $item->json['category_id'] == $google_pc_id;
            })->first()->json['category'];
            
            
            $custom_array[] = [
                $product->id,
                $product->json['title'][\LaravelLocalization::getCurrentLocale()],
                strip_tags($product->json['description']['short'][\LaravelLocalization::getCurrentLocale()]),
                $product->json['quantity'] > 0 ? 'in stock' : 'out of stock',
                'new',
                $product->json['price']['discount'] == null ?  ChuckEcommerce::formatPrice($product->json['price']['final']) : ChuckEcommerce::formatPrice($product->json['price']['discount']),
                url('/').'/'.$product->url,
                url('/').'/'.$product->images['image0']['url'],
                $brand->name,
                $fb_product_category,
                $google_product_category
            ];
        }

        return collect($custom_array);
    }
}