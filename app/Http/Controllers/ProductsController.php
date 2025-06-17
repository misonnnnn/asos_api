<?php

namespace App\Http\Controllers;

use App\Models\mainCategory;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProductsController extends Controller
{   

    function getProducts(Request $request) {
        $categoryId = $request->input('categoryid');
        $perPage = $request->input('per_page', 10); // default 10 per page

        $page = $request->input('page', 1);
        $cacheKey = "products.all.{$categoryId}.page.{$page}.per_page.{$perPage}";
        $ttl = now()->addHours(12); 
        $isFromCache = true;

        $products = Cache::remember($cacheKey, $ttl, function () use (&$isFromCache, $categoryId, $perPage) {
            $isFromCache = false;
            return product::where('category_id', $categoryId)->paginate($perPage);
        });

        $sizeInBytes = strlen(serialize($products));
        $sizeInKilobytes = $sizeInBytes / 1024;
        $size = "Cache size: {$sizeInBytes} bytes (~" . round($sizeInKilobytes, 2) . " KB)";

        $response = [
            'success' => true,
            'size' => $size,
            'isFromCache' => $isFromCache,
            'data' => $products,
        ];

        if(!empty($categoryId)){
            $categoryDetails = mainCategory::where('main_category_id', $categoryId)->first('name');

            $response['category_name'] = $categoryDetails->name;
            $response['category_id'] = $categoryId;
        }

        return response()->json($response);
    }

    function getProductDetails($path){
        $product = product::where('url', $path)->first();
        $success = true;

        //extra info holds all the relevant details in need
        $extra_info = !empty($product->extra_info) ? json_decode($product->extra_info, true) : []; 
        if(empty($extra_info)){
            $success = false;
            $product = [];
        }

        $product = $extra_info;

        return response()->json([
            'success' => $success,
            'full_path' => $path,
            'data' => $product
        ]);
    }

    public function getProductsBrands(){
        $success = true;
        $data = [];
        $images = env('API_IMAGES_LIST', null);
        if(!empty($images)){
            $data = [];
            $images = explode(',',$images);
            $path = env('APP_URL').'/images/brands/';
            foreach($images as $image){
                $data[] = $path.$image;
            }
        }else{
            $success = false;
        }
        return [
            'success' => $success,
            'data' => $data
        ];
    }



    //other info might be helpful
    //https://api.asos.com/product/catalogue/v4/productlooks?lookIds=208925295&store=US
}
