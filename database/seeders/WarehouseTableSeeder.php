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

            'type_id'=>1,
            'tot_weight'=>0
        ]);

        Warehouse::create([

            'type_id'=>2,
            'tot_weight'=>0
        ]);

        Warehouse::create([

            'type_id'=>3,
            'tot_weight'=>0  
        ]);

        Warehouse::create([

            'type_id'=>4,
            'tot_weight'=>0  
        ]);

        Warehouse::create([

            'type_id'=>5,
            'tot_weight'=>0
        ]);
    }
}
