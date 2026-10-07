<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Restaurant\FloorPlanController;
use App\Http\Controllers\Api\RestaurantAreaController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ModifierGroupController;
use App\Http\Controllers\ModifierOptionController;
use App\Http\Controllers\TaxController;


Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');

});

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

    Route::get('/restaurant/editor', [FloorPlanController::class, 'index'])
        ->name('restaurant.editor');

    Route::resource('users', UserController::class);

});

Route::get('/restaurant-areas', [RestaurantAreaController::class, 'index']);

Route::resource('product-categories',ProductCategoryController::class);



Route::resource('products',ProductController::class);
Route::resource('modifier-groups', ModifierGroupController::class);


Route::get(
    'modifier-groups/{modifier_group}/options',
    [ModifierOptionController::class, 'index']
)->name('modifier-options.index');

Route::post(
    'modifier-groups/{modifier_group}/options',
    [ModifierOptionController::class, 'store']
)->name('modifier-options.store');

Route::put(
    'modifier-groups/{modifier_group}/options/{modifier_option}',
    [ModifierOptionController::class, 'update']
)->name('modifier-options.update');

Route::delete(
    'modifier-groups/{modifier_group}/options/{modifier_option}',
    [ModifierOptionController::class, 'destroy']
)->name('modifier-options.destroy');

Route::resource('taxes', TaxController::class);