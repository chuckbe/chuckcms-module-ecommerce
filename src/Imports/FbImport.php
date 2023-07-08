<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Imports;

use Chuckbe\Chuckcms\Models\Content;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use ChuckRepeater;


class FbImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $content = [];
        $content_slug = 'facebook_product_category';
        if(Content::where('slug', $content_slug)->count() == 0){
            $fields_slug = [];
            foreach (collect($rows) as $index=>$row) 
            {
                if($index == 0){
                    foreach($row as $value)
                    {
                        $fields_slug[] = $value;
                    }
                }
            }
            $count = count($fields_slug);
            for ($i = 0; $i < $count; $i++) {
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
        $fb_categories = ChuckRepeater::for('facebook_product_category');
        foreach (collect($rows) as $index=>$row) 
        {   
            if($index !== 0){
                $exists = false;
                foreach($fb_categories as $fb_category)
                {
                    if($fb_category->json[collect($rows)->first()[0]] == $row[0] && $fb_category->json[collect($rows)->first()[1]] == $row[1]){
                        $exists = true;
                    }
                }
                if(!$exists){
                    $entry = collect([
                        $content_slug."_".collect($rows)->first()[0] => $row[0],
                        $content_slug."_".collect($rows)->first()[1] => $row[1],
                        "content_slug" => $content_slug,
                        "create" => "1"
                    ]);
                    $content = Content::where('slug', $content_slug)->first();
                    $content->storeEntry($entry);
                }
            }
        }
    }
}

