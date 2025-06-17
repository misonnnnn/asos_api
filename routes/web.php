<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// category (used for navbar)
Route::get('asos/v1/categories/', [CategoryController::class, 'getCategories'])->name('category.get');

// products
Route::get('asos/v1/products/', [ProductsController::class, 'getProducts'])->name('products.get');
Route::get('asos/v1/products/details/{path}', [ProductsController::class, 'getProductDetails'])->where('path', '.*') // allow slashes in {path}
    ->name('products.details.get');

Route::get('asos/v1/product-brands-images', [ProductsController::class, 'getProductsBrands'])->name('products.brands.images.get');