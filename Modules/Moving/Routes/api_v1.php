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

    Route::get('/customers', 'CustomerController@index');
    Route::post('/customers', 'CustomerController@store');
    Route::get('/customers/{id}', 'CustomerController@show');
    Route::put('/customers/{id}', 'CustomerController@update');
    Route::delete('/customers/{id}', 'CustomerController@destroy');

    Route::get('/rooms', 'RoomController@index');
    Route::post('/rooms', 'RoomController@store');
    Route::get('/rooms/{id}', 'RoomController@show');
    Route::put('/rooms/{id}', 'RoomController@update');
    Route::delete('/rooms/{id}', 'RoomController@destroy');

    Route::get('/items', 'ItemController@index');
    Route::post('/items', 'ItemController@store');
    Route::get('/items/{id}', 'ItemController@show');
    Route::put('/items/{id}', 'ItemController@update');
    Route::delete('/items/{id}', 'ItemController@destroy');

    Route::get('/orders', 'OrderController@index');
    Route::post('/orders', 'OrderController@store');
    Route::get('/orders/{id}', 'OrderController@show');
    Route::put('/orders/{id}', 'OrderController@update');
    Route::delete('/orders/{id}', 'OrderController@destroy');

    Route::get('/images', 'ImageController@index');
    Route::post('/images', 'ImageController@store');
    Route::get('/images/{id}', 'ImageController@show');
    Route::put('/images/{id}', 'ImageController@update');
    Route::delete('/images/{id}', 'ImageController@destroy');

    Route::get('/sellers', 'SellerController@index');
});
