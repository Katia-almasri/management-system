<?php

namespace Database\Seeders;

use App\Models\WarehouseType;
use Illuminate\Database\Seeder;

class WarehouseTypesTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WarehouseType::create([

            'warehouse_name'=>'براد صفري'
        ]);

        WarehouseType::create([

            'warehouse_name'=>'بحرات'
        ]);

        WarehouseType::create([

            'warehouse_name'=>'صاعقة 1'
        ]);

        WarehouseType::create([

            'warehouse_name'=>'صاعقة 2'
        ]);

        WarehouseType::create([

            'warehouse_name'=>'صاعقة 3'
        ]);

        WarehouseType::create([

            'warehouse_name'=>'تخزين'
        ]);

    }
}
