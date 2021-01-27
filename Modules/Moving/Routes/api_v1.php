<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['apiJwt', 'landlord'],
    'prefix' => 'moving'
], function () {

    Route::apiResource('/customers', 'CustomerController')->names([
        'index'     => 'customers.index',
        'store'     => 'customers.store',
        'show'      => 'customers.show',
        'update'    => 'customers.update',
        'destroy'   => 'customers.destroy'
    ]);

    Route::apiResource('/rooms', 'RoomController')->names([
        'index'     => 'rooms.index',
        'store'     => 'rooms.store',
        'show'      => 'rooms.show',
        'update'    => 'rooms.update',
        'destroy'   => 'rooms.destroy'
    ]);

    Route::apiResource('/items', 'ItemController')->names([
        'index'     => 'items.index',
        'store'     => 'items.store',
        'show'      => 'items.show',
        'update'    => 'items.update',
        'destroy'   => 'items.destroy'
    ]);

    Route::apiResource('/orders', 'OrderController')->names([
        'index'     => 'orders.index',
        'store'     => 'orders.store',
        'show'      => 'orders.show',
        'update'    => 'orders.update',
        'destroy'   => 'orders.destroy'
    ]);

    Route::apiResource('/images', 'ImageController')->names([
        'index'     => 'images.index',
        'store'     => 'images.store',
        'show'      => 'images.show',
        'update'    => 'images.update',
        'destroy'   => 'images.destroy'
    ]);

    Route::get('/sellers', 'SellerController@index')->name('sellers.index');

    Route::apiResource('/packings', 'PackingController')->names([
        'index'     => 'packings.index',
        'store'     => 'packings.store',
        'show'      => 'packings.show',
        'update'    => 'packings.update',
        'destroy'   => 'packings.destroy'
    ]);
});
