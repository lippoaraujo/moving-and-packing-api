<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'system'
], function () {
    Route::post('/tenants', 'TenantController@store')->name('tenants.store');
});

Route::group([
    'middleware' => ['apiJwt', 'landlord'],
    'prefix' => 'system'
], function () {

    Route::put('/tenants/{tenant}', 'TenantController@update')->name('tenants.update');
    Route::group([
        'middleware' => ['super_admin']
    ], function () {

        Route::apiResource('/tenants', 'TenantController')->except(['store', 'update'])->names([
            'index'     => 'tenants.index',
            'show'      => 'tenants.show',
            'destroy'   => 'tenants.destroy'
        ]);
        Route::apiResource('/modules', 'ModuleController')->names([
            'index'     => 'modules.index',
            'store'     => 'modules.store',
            'show'      => 'modules.show',
            'update'    => 'modules.update',
            'destroy'   => 'modules.destroy'
        ]);
    });

    Route::apiResource('/permissions', 'PermissionController')->names([
        'index'     => 'permissions.index',
        'store'     => 'permissions.store',
        'show'      => 'permissions.show',
        'update'    => 'permissions.update',
        'destroy'   => 'permissions.destroy'
    ]);

    Route::apiResource('/roles', 'RoleController')->names([
        'index'     => 'roles.index',
        'store'     => 'roles.store',
        'show'      => 'roles.show',
        'update'    => 'roles.update',
        'destroy'   => 'roles.destroy'
    ]);

    Route::get('/users/permissions', 'UserController@permission')->name('users.permission');
    Route::apiResource('/users', 'UserController')->names([
        'index'     => 'users.index',
        'store'     => 'users.store',
        'show'      => 'users.show',
        'update'    => 'users.update',
        'destroy'   => 'users.destroy'
    ]);
});
