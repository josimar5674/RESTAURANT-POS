<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Restaurant\FloorPlanController;
use App\Http\Controllers\Api\RestaurantAreaController;



Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');

});

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

});


Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/restaurant/editor', [FloorPlanController::class, 'index'])
        ->name('restaurant.editor');

});


Route::get('/restaurant-areas', [RestaurantAreaController::class, 'index']);

