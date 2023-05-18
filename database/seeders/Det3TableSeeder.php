<?php

namespace Database\Seeders;

use App\Models\DetonatorFrige3;
use Illuminate\Database\Seeder;

class Det3TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetonatorFrige3::create([

            'warehouse_id'=>1,
            'weight'=>0
        ]);

        DetonatorFrige3::create([

            'warehouse_id'=>2,
            'weight'=>0
        ]);

        DetonatorFrige3::create([

            'warehouse_id'=>3,
            'weight'=>0
        ]);

        DetonatorFrige3::create([

            'warehouse_id'=>4,
            'weight'=>0
        ]);

        DetonatorFrige3::create([

            'warehouse_id'=>5,
            'weight'=>0
        ]);

    }
}
