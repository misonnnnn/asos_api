<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProductsController extends Controller
{   

    function getProducts(Request $request) {
        $categoryId = $request->input('categoryid');

        $params = [
            "country"     => "US",
            "store"       => "US",
            "offset"      => 0,
            "limit"       => 1000,
            "categoryId"  => $categoryId,
            "sizeSchema"  => "US",
            "lang"        => "en-US",
        ];
        $build_params = http_build_query($params);

        $apiKeys = Cache::get('api_keys', []);

        foreach ($apiKeys as $key => $isLimitReached) {
            if ($isLimitReached) continue;

            $response = Http::withHeaders([
                'x-rapidapi-key' => $key,
            ])->get("https://asos2.p.rapidapi.com/products/v2/list?{$build_params}");

            $body = json_decode($response->body(), true);

            if (isset($body['data']['message']) &&
                str_contains($body['data']['message'], 'exceeded the MONTHLY quota')) {

                // Mark key as used up
                $apiKeys[$key] = true;
                Cache::put('api_keys', $apiKeys);
                continue;
            }

            return [
                'success' => true,
                'data' => $body,
                'used_key' => $key
            ];
        }

        return response()->json([
            'success' => false,
            'message' => 'All API keys have reached their monthly limit.'
        ], 429);
    }


    //other info might be helpful
    //https://api.asos.com/product/catalogue/v4/productlooks?lookIds=208925295&store=US
}
