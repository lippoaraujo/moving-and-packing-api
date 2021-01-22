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

    Route::get('/roles', 'RoleController@index')->middleware('super_admin');
    Route::post('/roles', 'RoleController@store');
    Route::get('/roles/{id}', 'RoleController@show');
    Route::put('/roles/{id}', 'RoleController@update');
    Route::delete('/roles/{id}', 'RoleController@destroy');

    Route::get('/tenants', 'TenantController@index');
    Route::post('/tenants', 'TenantController@store');
    Route::get('/tenants/{id}', 'TenantController@show');
    Route::put('/tenants/{id}', 'TenantController@update');
    Route::delete('/tenants/{id}', 'TenantController@destroy');

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

    Route::get('/dashboard', 'DashboardController@show');
});
