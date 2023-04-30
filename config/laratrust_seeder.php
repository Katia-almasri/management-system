<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'ceo' => [
            'warehouses' => 'r',
            'invoices' => 'r,u',
            'profile' => 'c,r,u,d'
        ],
        'general-manager' => [
            'warehouses' => 'r',
            'invoices' => 'r'
        ],

        'Purchasing-and-Sales-manager' => [
            'farms'=>'r,d',
        ],

        'Mechanism-Coordinator' => [
            'truck'=>'r,d,u,c',
            'drivers'=>'r,d,u,c'
        ],

        'libra-commander' => [
            'Receipt statement'=>'r,d,u,c',
            'statement after weight'=>'r,d,u,c'
        ],

        'Accounting-Manager' => [
            'Financial reports'=>'r'
        ],

        'Production_Manager' => [
            'commander'=>'r',
            'note' =>'r',
            'warehouse' => 'r'
        ],

        'slaughter_supervisor' => [
            'commander'=>'r'
        ],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
