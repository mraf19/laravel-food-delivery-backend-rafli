<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// get user information
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
//get all user
Route::get('/user/all', [AuthController::class, 'get_all_user']);

//login
Route::post('/login', [AuthController::class, 'login']);

//logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// user registration
Route::post('/user/register', [AuthController::class, 'user_register']);

//update latlong user
Route::put('/user/update-latlong', [AuthController::class, 'update_latlong'])->middleware('auth:sanctum');

//get all restaurant
Route::get('/restaurant', [AuthController::class, 'get_all_restaurant']);

//restaurant registration
Route::post('/restaurant/register', [AuthController::class, 'restaurant_register']);

//driver registration
Route::post('/driver/register', [AuthController::class, 'driver_register']);

//products crud
Route::apiResource('/products', ProductController::class)->middleware('auth:sanctum');

//create order
Route::post('/order', [OrderController::class, 'create_order'])->middleware('auth:sanctum');

//get order by user id
Route::get('/order/user', [OrderController::class, 'order_history'])->middleware('auth:sanctum');

//update purchase status
Route::put('/order/user/update-status/{id}', [OrderController::class, 'update_purchase_status'])->middleware('auth:sanctum');

//get order by restaurant id
Route::get('/order/restaurant', [OrderController::class, 'get_order_by_status'])->middleware('auth:sanctum');

//update order for restaurant
Route::put('/order/restaurant/update-status/{id}', [OrderController::class, 'update_order_status'])->middleware('auth:sanctum');

//get order by driver id
Route::get('/order/driver', [OrderController::class, 'get_order_by_status_for_driver'])->middleware('auth:sanctum');

//update order status for driver
Route::put('/order/driver/update-status/{id}', [OrderController::class, 'update_order_status_for_driver'])->middleware('auth:sanctum');
