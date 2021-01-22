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

    Route::group([
        'middleware' => ['super_admin']
    ], function() {
        Route::get('/tenants', 'TenantController@index')->name('tenants-index');
        Route::post('/tenants', 'TenantController@store')->name('tenants-store');
        Route::get('/tenants/{id}', 'TenantController@show')->name('tenants-show');
        Route::put('/tenants/{id}', 'TenantController@update')->name('tenants-update');
        Route::delete('/tenants/{id}', 'TenantController@destroy')->name('tenants-destroy');

        Route::get('/modules', 'ModuleController@index')->name('modules-index');
        Route::post('/modules', 'ModuleController@store')->name('modules-store');
        Route::get('/modules/{id}', 'ModuleController@show')->name('modules-show');
        Route::put('/modules/{id}', 'ModuleController@update')->name('modules-update');
        Route::delete('/modules/{id}', 'ModuleController@destroy')->name('modules-destroy');
    });

    Route::get('/roles', 'RoleController@index')->name('roles-index');
    Route::post('/roles', 'RoleController@store')->name('roles-store');
    Route::get('/roles/{id}', 'RoleController@show')->name('roles-show');
    Route::put('/roles/{id}', 'RoleController@update')->name('roles-update');
    Route::delete('/roles/{id}', 'RoleController@destroy')->name('roles-destroy');


    Route::get('/users', 'UserController@index')->name('users-index');
    Route::post('/users', 'UserController@store')->name('users-store');
    Route::get('/users/{id}', 'UserController@show')->name('users-show');
    Route::put('/users/{id}', 'UserController@update')->name('users-update');
    Route::delete('/users/{id}', 'UserController@destroy')->name('users-destroy');

    Route::get('/dashboard', 'DashboardController@show')->name('dashboard-show');
});
