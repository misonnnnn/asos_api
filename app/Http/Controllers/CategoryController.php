<?php

namespace App\Http\Controllers;

use App\Models\parentCategory;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function getCategories(){
        $cacheKey = 'categories.all';
        $ttl = now()->addHours(12); 
        $isFromCache = true;
        $categories = Cache::remember($cacheKey, $ttl, function () use(&$isFromCache) {
            $isFromCache = false;
            return parentCategory::with([
                'subCategories.childCategories.mainCategories'
            ])->get();
        });

        $sizeInBytes = strlen(serialize($categories));
        $sizeInKilobytes = $sizeInBytes / 1024;
        $size = "Cache size: {$sizeInBytes} bytes (~" . round($sizeInKilobytes, 2) . " KB)";

        return response()->json([
            'success' => true,
            'size' => $size,
            'isFromCache' => $isFromCache,
            'data' => $categories,
        ]);
    }
}
