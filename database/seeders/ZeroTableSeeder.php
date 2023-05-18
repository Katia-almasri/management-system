<?php

namespace Database\Seeders;

use App\Models\ZeroFrige;
use Illuminate\Database\Seeder;

class ZeroTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZeroFrige::create([

            'warehouse_id'=>1,
            'weight'=>0
        ]);

        ZeroFrige::create([

            'warehouse_id'=>2,
            'weight'=>0
        ]);

        ZeroFrige::create([

            'warehouse_id'=>3,
            'weight'=>0
        ]);

        ZeroFrige::create([

            'warehouse_id'=>4,
            'weight'=>0
        ]);

        ZeroFrige::create([

            'warehouse_id'=>5,
            'weight'=>0
        ]);

       
    }
}
