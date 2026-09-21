<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantObjectController;
use App\Http\Controllers\Api\RestaurantAreaController;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\FloorPlanController;

Route::apiResource(
    'restaurant-objects',
    RestaurantObjectController::class
);


Route::apiResource('restaurant-areas', RestaurantAreaController::class);
Route::post('/device/register', [DeviceController::class, 'register']);
Route::post('/device/check', [DeviceController::class, 'check']);
Route::post('/auth/pin', [AuthController::class, 'loginWithPin']);
Route::post('/login/pin', [AuthController::class, 'loginWithPin']);
Route::get('/floor-plan', [FloorPlanController::class, 'index']);