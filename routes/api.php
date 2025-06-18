<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// category (used for navbar)
Route::get('asos/v1/categories/', [CategoryController::class, 'getCategories'])->name('category.get');

// products
Route::get('asos/v1/products/', [ProductsController::class, 'getProducts'])->name('products.get');
Route::get('asos/v1/products/details/{path}', [ProductsController::class, 'getProductDetails'])->where('path', '.*') // allow slashes in {path}
    ->name('products.details.get');

Route::get('asos/v1/product-brands-images', [ProductsController::class, 'getProductsBrands'])->name('products.brands.images.get');




//auth
Route::post('asos/v1/login', [AuthController::class, 'login']);
Route::post('asos/v1/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('asos/v1/me', [AuthController::class, 'me']);
    Route::post('asos/v1/logout', [AuthController::class, 'logout']);
    Route::post('asos/v1/refresh', [AuthController::class, 'refresh']);

    // Protect your other routes here
    // Route::get('/user-orders', [OrderController::class, 'index']);
});