<?php

use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\ProductsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/products', ProductsController::class)
    ->only(['index', 'show']);


Route::apiResource('/customers', CustomersController::class)
    ->only(['index', 'show']);

Route::apiResource('/orders', OrdersController::class)
    ->except(['update']);

Route::put('/orders/{id}/add', [OrdersController::class, 'update']);
Route::delete('/orders/{id}/remove', [OrdersController::class, 'update']);

Route::post('/orders/{id}/pay', [OrdersController::class, 'payOrder']);
