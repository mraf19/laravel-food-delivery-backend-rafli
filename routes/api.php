<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user/all', [AuthController::class, 'get_all_user']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/user/register', [AuthController::class, 'user_register']);
Route::post('/restaurant/register', [AuthController::class, 'restaurant_register']);
Route::post('/driver/register', [AuthController::class, 'driver_register']);
