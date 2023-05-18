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

            'warehouse_id'=>1,
            'weight'=>0
        ]);

        Lake::create([

            'warehouse_id'=>2,
            'weight'=>0
        ]);

        Lake::create([

            'warehouse_id'=>3,
            'weight'=>0
        ]);

        Lake::create([

            'warehouse_id'=>4,
            'weight'=>0
        ]);

        Lake::create([

            'warehouse_id'=>5,
            'weight'=>0
        ]);

    }
}
