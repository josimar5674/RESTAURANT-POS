<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantObjectController;
use App\Http\Controllers\Api\RestaurantAreaController;

Route::apiResource(
    'restaurant-objects',
    RestaurantObjectController::class
);



Route::apiResource('restaurant-areas', RestaurantAreaController::class);