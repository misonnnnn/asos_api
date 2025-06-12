<?php

namespace App\Console\Commands;

use App\Models\mainCategory;
use App\Models\product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class syncAsosProductList extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asos:sync-productlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->syncProductList();
    }

   

    const API_KEY = '55f66f5570msh1993f47712da4d2p1045f6jsn3d97141c2597';

    public function syncProductList()
    {


        $categories = MainCategory::where('main_category_id', '!=', 0)->get();

        foreach ($categories as $category) {
            if ($category->is_product_list_already_sync === 'done') {
                continue;
            }

            $params = [
                "country"     => "US",
                "store"       => "US",
                "offset"      => 0,
                "limit"       => 1000,
                "categoryId"  => $category->main_category_id,
                "sizeSchema"  => "US",
                "lang"        => "en-US",
            ];

            $query = http_build_query($params);

            $response = Http::withHeaders([
                'x-rapidapi-key' => self::API_KEY,
            ])->get("https://asos2.p.rapidapi.com/products/v2/list?$query");

            $body = json_decode($response->body(), true);
            if (empty($body['products'])) {
                // Add key to banned list in cache
                $this->warn("Could not sync category ID {$category->id} - {$category->name}, error empty productlist");
                continue;
            }


            if ($response->ok()) {
                // ✅ TODO: Save product data if needed
                $category->is_product_list_already_sync = 'done';
                $category->save();

                $productsToUpsert = [];

                foreach ($body['products'] as $product) {
                    $productsToUpsert[] = [
                        'external_unique_id' => $product['id'],
                        'product_code'       => $product['productCode'],
                        'category_id'       => $category['main_category_id'] ?? null,
                        'name'               => $product['name'],
                        'brand_name'         => $product['brandName'],
                        'price'              => $product['price']['current']['value'] ?? null,
                        'price_json'         => json_encode($product['price']),
                        'colour'             => $product['colour'],
                        'url'                => $product['url'],
                        'status'             => 'active',
                        'additional_images_urls' => json_encode($product['additionalImageUrls']),
                        'extra_info'         => json_encode($product),
                        'updated_at'         => now(),
                    ];
                }

                product::upsert(
                    $productsToUpsert,
                    ['external_unique_id'], // Unique constraint/identifier
                    [ // Fields to update if exists
                        'product_code', 'name', 'brand_name', 'price', 'price_json',
                        'colour', 'url', 'status', 'additional_images_urls', 'extra_info', 'updated_at'
                    ]
                );
                break;
            }
            $this->info("Successfull sync product list for {$category->id} - {$category->name}");
            
        }

        return [
            'success' => true,
            'message' => 'Sync completed. Some categories may be skipped if all API keys are banned.',
        ];
    }
}
