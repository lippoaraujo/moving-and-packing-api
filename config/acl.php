<?php

return [

    'admins' => [
        'admin@admin.com',
    ],

    'tenant' => [
        'default_role' => 'Master'
    ],

    'permissions' => [
        'can' => [
            'role-list',
            'role-create',
            'role-show',
            'role-edit',
            'role-delete',

            'permission-list',
            'permission-create',
            'permission-show',
            'permission-edit',
            'permission-delete',

            'user-list',
            'user-create',
            'user-show',
            'user-edit',
            'user-delete',

            'customer-list',
            'customer-create',
            'customer-show',
            'customer-edit',
            'customer-delete',

            'item-list',
            'item-create',
            'item-show',
            'item-edit',
            'item-delete',

            'room-list',
            'room-create',
            'room-show',
            'room-edit',
            'room-delete',

            'packing-list',
            'packing-create',
            'packing-show',
            'packing-edit',
            'packing-delete',

            'order-list',
            'order-create',
            'order-show',
            'order-edit',
            'order-delete',

            'seller-list'
        ],
        'modules' => [
            'system-module',
            'moving-module'
        ]
    ]
];
