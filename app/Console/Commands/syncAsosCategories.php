<?php

namespace App\Console\Commands;

use App\Models\childCategory;
use App\Models\mainCategory;
use App\Models\parentCategory;
use App\Models\subCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class syncAsosCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asos:sync-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    const API_KEY = 'b3fd5fe006msh1bc06bce62feddap1e90cfjsn4ba5be0a23a0';

    /**
     * Execute the console command.
     */
    public function handle(){
        $response = Http::withHeaders([
            'x-rapidapi-key' => self::API_KEY,
            // 'x-rapidapi-host' => 'asos2.p.rapidapi.com'
        ])->get('https://asos2.p.rapidapi.com/categories/list?country=US&lang=en-US');

        if($response->successful()){
            $data = json_decode($response->body(),true);

            $navigation = (array) $data['navigation'];
            $this->syncParentCategory($navigation);
        }else{
            logger('API Error: ' . $response->status());
            dd($response->body());
        }

    }   

    public function syncParentCategory($data){
        foreach($data as $parentCategory){
            $parent_category_id = $parentCategory['id'];
            $parent_category_children_data = $parentCategory['children'];


            $new_data = [
                'name' => $parentCategory['content']['title'],
            ];

            $this->line(json_encode($new_data));
            parentCategory::updateOrCreate(['external_unique_id' => $parent_category_id],$new_data);

            $this->syncSubCategory($parent_category_id, $parent_category_children_data);
        }
    }

    public function syncSubCategory($parent_category_id, $data){
        foreach ($data as $subCategoryData){
            if($subCategoryData['content']['title'] == 'Categories'){
                foreach($subCategoryData['children'] as $subCategory){
                    $new_data = [
                        'parent_category_id' => $parent_category_id,
                        'name' => $subCategory['content']['title'],
                        'extra_info' => json_encode($subCategory['content']),
                    ];

                    $this->line(json_encode($new_data));
                    subCategory::updateOrCreate(['external_unique_id' => $subCategory['id']],$new_data);

                    $this->syncChildCategory($parent_category_id, $subCategory['id'], $subCategory['children']);
                }
            }
        }
    }

    public function syncChildCategory($parent_category_id, $sub_category_id, $data){
        foreach($data as $childCategory){
            $new_data = [
                'parent_category_id' => $parent_category_id,
                'sub_category_id' => $sub_category_id,
                'name' => $childCategory['content']['title'],
                'extra_info' => json_encode($childCategory['content']),
            ];
            childCategory::updateOrCreate(['external_unique_id' => $childCategory['id']],$new_data);
            $this->line(json_encode($new_data));
            $this->syncMainCategory($parent_category_id, $sub_category_id, $childCategory['id'],$childCategory['children']);
        }
    }

    public function syncMainCategory($parent_category_id, $sub_category_id ,$child_category_id, $data){
        foreach($data as $childCategory){
            $new_data = [
                'main_category_id' => $childCategory['link']['categoryId'] ?? 0,
                'parent_category_id' => $parent_category_id,
                'child_category_id' => $child_category_id,
                'sub_category_id' => $sub_category_id,
                'name' => $childCategory['content']['title'],
                'extra_info' => json_encode($childCategory['content']),
            ];
            mainCategory::updateOrCreate(['external_unique_id' => $childCategory['id']],$new_data);
            $this->line(json_encode($new_data));
        }
    }
}
