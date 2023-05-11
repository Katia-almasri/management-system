<?php

namespace Database\Seeders;

use App\Models\Lake;
use Illuminate\Database\Seeder;

class LakeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lake::create([

            'warehouse_id'=>1
        ]);

        Lake::create([

            'warehouse_id'=>2
        ]);

        Lake::create([

            'warehouse_id'=>3
        ]);

        Lake::create([

            'warehouse_id'=>4
        ]);

        Lake::create([

            'warehouse_id'=>5
        ]);

    }
}
