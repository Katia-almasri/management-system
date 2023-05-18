<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([

            'warehouse_id'=>1,
            'weight'=>0
        ]);

        Store::create([

            'warehouse_id'=>2,
            'weight'=>0
        ]);

        Store::create([

            'warehouse_id'=>3,
            'weight'=>0
        ]);

        Store::create([

            'warehouse_id'=>4,
            'weight'=>0
        ]);

        Store::create([

            'warehouse_id'=>5,
            'weight'=>0
        ]);
    }
}
