<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Imports;

use Chuckbe\Chuckcms\Models\Content;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use ChuckRepeater;


class GpcImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $content = [];
        $content_slug = 'google_product_category';
        if(Content::where('slug', $content_slug)->count() == 0){
            $fields_slug = ['category_id', 'category'];
            $count = count($fields_slug);
            for ($i = 0; $i < $count; $i++) 
            {
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['label'] = ucwords(str_replace('_',' ',$fields_slug[$i]));
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['type'] = 'text';
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['class'] = 'form-control';
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['placeholder'] = '';
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['validation'] = 'required';
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['value'] = '';
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['attributes']['id'] = '';
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['required'] = true;
                $content['fields'][$content_slug.'_'.$fields_slug[$i]]['table'] = true;
            }
            $content['actions']['store'] = "true";
            $content['actions']['detail'] = "false";
            $content['files'] = "false";
            Content::create([   
                'slug'    => $content_slug,
                'type'    => 'repeater',
                'content' => $content, 
            ]);
        }
        $google_categories = ChuckRepeater::for('google_product_category');
        foreach (collect($rows) as $index=>$row)
        {
            $exists = false;
            $cat_name = implode(" > ", array_diff(array_slice($row->toArray(), 1), array(null)));
            foreach($google_categories as $google_category)
            {
                if($google_category->json['category_id'] == $row[0] && $google_category->json['category'] == $cat_name)
                {
                    $exists = true;
                }
            }
            if(!$exists)
            {
                $entry = collect([
                    $content_slug."_category_id" => $row[0],
                    $content_slug."_category" => $cat_name,
                    "content_slug" => $content_slug,
                    "create" => "1"
                ]);
                $content = Content::where('slug', $content_slug)->first();
                $content->storeEntry($entry);
            }
        }
    }
}

