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
            'profile' => 'c,r,u,d',
            'output_slaughter' => 'r',
            'output_cutting' => 'r',
            'output_manufacturing' => 'r',
            'content lake' => 'r',
            'content zero-frige' => 'r',
            'content det-1' => 'r',
            'content det-2' => 'r',
            'content det-3' => 'r',
            'content store' => 'r'
        ],

        'general-manager' => [
            'warehouses' => 'r',
            'invoices' => 'r'
        ],

        'Purchasing-and-Sales-manager' => [
            'farms'=>'r,d',
            'note' =>'r,c',
            'warehouse' => 'r',
            'statement after weight'=>'r'
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
            'note' =>'r,c',
            'warehouse' => 'r',
            'commands for warehouse'=> 'r,d,u,c',
            'output_slaughter' => 'r',
            'output_cutting' => 'r',
            'output_manufacturing' => 'r'
        ],

        'slaughter_supervisor' => [
            'commander'=>'r',
            'warehouse' => 'r',
            'output_slaughter' => 'r,c'
        ],



        'cutting_supervisor' => [
            'input'=>'r',
            'output'=>'c',
            'command' =>'u',
            'warehouse' => 'r',
            'output_cutting' => 'r,c'
        ],

        'Manufacturing_Supervisor' => [
            'input'=>'r',
            'output'=>'c',
            'command' =>'u',
            'warehouse' => 'r',
            'output_manufacturing' => 'r,c'
        ],

        'warehouse_supervisor' => [
            'warehouse'=>'r,d,u,c',
            'commands for warehouse'=>'r',
            'content lake' => 'r',
            'content zero-frige' => 'r',
            'content det-1' => 'r',
            'content det-2' => 'r',
            'content det-3' => 'r',
            'content store' => 'r'


        ],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
