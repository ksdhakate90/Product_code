<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\ProductController;

/*C: 
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//admin Route
Route::POST('/login', [AdminAuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/admin/sellers', [SellerController::class, 'store']);
    Route::get('/admin/sellers/list', [SellerController::class, 'index']);
    Route::post('/seller/login', [SellerController::class, 'sellerLogin']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/list', [ProductController::class, 'index']);
    Route::get('/products/{id}/pdf', [ProductController::class, 'viewPdf']);
    Route::post('/products/{id}', [ProductController::class, 'destroy']);
});