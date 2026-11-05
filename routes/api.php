<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantObjectController;
use App\Http\Controllers\Api\RestaurantAreaController;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\FloorPlanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\ProductCatalogController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PaymentController;


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
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/table/{tableId}',[OrderController::class, 'showForTable']);
Route::get('/catalog', [ProductCatalogController::class, 'index']);
Route::get('/catalog/products/{productId}', [ProductCatalogController::class, 'show']);
Route::post('/orders/{orderId}/items',[OrderController::class, 'addItem']);
Route::put('/orders/{orderId}/items/{itemId}',[OrderController::class, 'updateItem']);
Route::delete('/orders/{orderId}/items/{itemId}',[OrderController::class, 'deleteItem']);
Route::post('/orders/{orderId}/send-to-kitchen', [OrderController::class, 'sendToKitchen']);
Route::get('/orders/table/{tableId}/active', [OrderController::class, 'showActiveForTable']);
Route::get('/orders/{orderId}', [OrderController::class, 'show']);

//mark item as received
Route::post(
    '/orders/{orderId}/items/{itemId}/received',
    [OrderController::class, 'markItemReceived']
);

Route::get(
    '/tables/{tableId}/checkout',
    [OrderController::class, 'checkout']
);



Route::post(
    '/tables/{tableId}/checkout/accounts',
    [OrderController::class, 'createCheckoutAccount']
);
Route::get(

    '/checkout-accounts/{accountId}',

    [OrderController::class, 'showCheckoutAccount']

);



Route::get(
    '/payment-methods/active',
    [PaymentMethodController::class, 'activeMethods']
);



Route::post(
    '/checkout-accounts/{accountId}/payments',
    [PaymentController::class, 'store']
);