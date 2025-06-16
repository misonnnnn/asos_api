<?php

namespace App\Console\Commands;

use App\Models\product;
use Illuminate\Console\Command;

class rebuildProductsUrlPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asos:rebuildProductsUrlPath';

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
        product::chunk(500, function ($products) {
            foreach ($products as $product) {
                $updatedUrl = explode('#', $product->url)[0];

                if ($product->url !== $updatedUrl) {
                    $product->url = $updatedUrl;
                    $product->save();
                    $this->info("Product ID: {$product->id} rebuilding url success");
                }
            }
        });
    }
}
