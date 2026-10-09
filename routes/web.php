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
use App\Http\Controllers\RoleController;


/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');

});


/*
|--------------------------------------------------------------------------
| Sistema protegido
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Usuarios
    |--------------------------------------------------------------------------
    */

 Route::resource('users', UserController::class)
    ->middlewareFor('index', 'permission:users.view')
    ->middlewareFor('create', 'permission:users.create')
    ->middlewareFor('store', 'permission:users.create')
    ->middlewareFor('edit', 'permission:users.edit')
    ->middlewareFor('update', 'permission:users.edit')
    ->middlewareFor('destroy', 'permission:users.delete');

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

   Route::resource('roles', RoleController::class)
    ->middlewareFor('index', 'permission:roles.view')
    ->middlewareFor('create', 'permission:roles.create')
    ->middlewareFor('store', 'permission:roles.create')
    ->middlewareFor('edit', 'permission:roles.edit')
    ->middlewareFor('update', 'permission:roles.edit')
    ->middlewareFor('destroy', 'permission:roles.delete');


    /*
    |--------------------------------------------------------------------------
    | Áreas / Salón
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/restaurant-areas',
        [RestaurantAreaController::class, 'index']
    )->middleware('permission:tables.view');


    /*
    |--------------------------------------------------------------------------
    | Editor del salón
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/restaurant/editor',
        [FloorPlanController::class, 'index']
    )->name('restaurant.editor')
     ->middleware('permission:tables.edit');


    /*
    |--------------------------------------------------------------------------
    | Categorías de productos
    |--------------------------------------------------------------------------
    */
Route::resource(
    'product-categories',
    ProductCategoryController::class
)
    ->middlewareFor('index', 'permission:product_categories.view')
    ->middlewareFor('create', 'permission:product_categories.create')
    ->middlewareFor('store', 'permission:product_categories.create')
    ->middlewareFor('edit', 'permission:product_categories.edit')
    ->middlewareFor('update', 'permission:product_categories.edit')
    ->middlewareFor('destroy', 'permission:product_categories.delete');


    /*
    |--------------------------------------------------------------------------
    | Productos
    |--------------------------------------------------------------------------
    */
Route::resource('products', ProductController::class)
    ->middlewareFor('index', 'permission:products.view')
    ->middlewareFor('create', 'permission:products.create')
    ->middlewareFor('store', 'permission:products.create')
    ->middlewareFor('edit', 'permission:products.edit')
    ->middlewareFor('update', 'permission:products.edit')
    ->middlewareFor('destroy', 'permission:products.delete');


    /*
    |--------------------------------------------------------------------------
    | Grupos de modificadores
    |--------------------------------------------------------------------------
    */

   Route::resource(
    'modifier-groups',
    ModifierGroupController::class
)
    ->middlewareFor('index', 'permission:modifier_groups.view')
    ->middlewareFor('create', 'permission:modifier_groups.create')
    ->middlewareFor('store', 'permission:modifier_groups.create')
    ->middlewareFor('edit', 'permission:modifier_groups.edit')
    ->middlewareFor('update', 'permission:modifier_groups.edit')
    ->middlewareFor('destroy', 'permission:modifier_groups.delete');


    /*
    |--------------------------------------------------------------------------
    | Opciones de modificadores
    |--------------------------------------------------------------------------
    */

    Route::get(
        'modifier-groups/{modifier_group}/options',
        [ModifierOptionController::class, 'index']
    )
        ->name('modifier-options.index')
        ->middleware('permission:modifier_options.view');

    Route::post(
        'modifier-groups/{modifier_group}/options',
        [ModifierOptionController::class, 'store']
    )
        ->name('modifier-options.store')
        ->middleware('permission:modifier_options.create');

    Route::put(
        'modifier-groups/{modifier_group}/options/{modifier_option}',
        [ModifierOptionController::class, 'update']
    )
        ->name('modifier-options.update')
        ->middleware('permission:modifier_options.edit');

    Route::delete(
        'modifier-groups/{modifier_group}/options/{modifier_option}',
        [ModifierOptionController::class, 'destroy']
    )
        ->name('modifier-options.destroy')
        ->middleware('permission:modifier_options.delete');


    /*
    |--------------------------------------------------------------------------
    | Impuestos
    |--------------------------------------------------------------------------
    */

    Route::resource('taxes', TaxController::class)
    ->middlewareFor('index', 'permission:taxes.view')
    ->middlewareFor('create', 'permission:taxes.create')
    ->middlewareFor('store', 'permission:taxes.create')
    ->middlewareFor('edit', 'permission:taxes.edit')
    ->middlewareFor('update', 'permission:taxes.edit')
    ->middlewareFor('destroy', 'permission:taxes.delete');

});