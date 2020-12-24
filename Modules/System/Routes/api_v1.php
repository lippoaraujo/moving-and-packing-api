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
    'prefix' => 'system'
], function () {

    Route::get('/tenants', 'TenantController@index');
    Route::post('/tenants', 'TenantController@store');
    Route::get('/tenants/{id}', 'TenantController@show');
    Route::put('/tenants/{id}', 'TenantController@update');
    Route::delete('/tenants/{id}', 'TenantController@destroy');

    Route::get('/usergroups', 'UsergroupController@index');
    Route::post('/usergroups', 'UsergroupController@store');
    Route::get('/usergroups/{id}', 'UsergroupController@show');
    Route::put('/usergroups/{id}', 'UsergroupController@update');
    Route::delete('/usergroups/{id}', 'UsergroupController@destroy');

    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    Route::get('/users/{id}', 'UserController@show');
    Route::put('/users/{id}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@destroy');

    Route::get('/modules', 'ModuleController@index');
    Route::post('/modules', 'ModuleController@store');
    Route::get('/modules/{id}', 'ModuleController@show');
    Route::put('/modules/{id}', 'ModuleController@update');
    Route::delete('/modules/{id}', 'ModuleController@destroy');

    Route::get('/routes', 'RouteController@index');
    Route::post('/routes', 'RouteController@store');
    Route::get('/routes/{id}', 'RouteController@show');
    Route::put('/routes/{id}', 'RouteController@update');
    Route::delete('/routes/{id}', 'RouteController@destroy');

    Route::get('/actions', 'ActionController@index');
    Route::post('/actions', 'ActionController@store');
    Route::get('/actions/{id}', 'ActionController@show');
    Route::put('/actions/{id}', 'ActionController@update');
    Route::delete('/actions/{id}', 'ActionController@destroy');

    Route::get('/dashboard', 'DashboardController@show');
});
