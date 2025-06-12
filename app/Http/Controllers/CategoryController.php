<?php

namespace App\Http\Controllers;

use App\Models\parentCategory;

class CategoryController extends Controller
{
    public function getCategories(){
        $categories = parentCategory::with([
            'subCategories.childCategories.mainCategories'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
