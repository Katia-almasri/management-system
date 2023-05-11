<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([

            'type_id'=>1   
        ]);

        Warehouse::create([

            'type_id'=>2   
        ]);

        Warehouse::create([

            'type_id'=>3   
        ]);

        Warehouse::create([

            'type_id'=>4   
        ]);

        Warehouse::create([

            'type_id'=>5   
        ]);
    }
}
