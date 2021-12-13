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
        foreach(ChuckProduct::all() as $product){
            $brand = ChuckRepeater::for('brands')->where('id', $product->json['brand'])->first();
            $fb_product_category_id = ChuckRepeater::for('collections')->where('id', $product->json['collection'][0])->first()->json['fb_product_category'];
            $fb_product_category = '';
            foreach(ChuckRepeater::for('facebook_product_category') as $fb_category){
                if($fb_category->json['category_id'] == $fb_product_category_id){
                    $fb_product_category = $fb_category->json['category'];
                }
            }
            $google_product_category_id = ChuckRepeater::for('collections')->where('id', $product->json['collection'][0])->first()->json['google_product_category'];
            $google_product_category = '';
            foreach(ChuckRepeater::for('facebook_product_category') as $google_category){
                if($google_category->json['category_id'] == $google_product_category_id){
                    $google_product_category = $google_category->json['category'];
                }
            }
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