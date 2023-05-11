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

            'warehouse_id'=>1
        ]);

        ZeroFrige::create([

            'warehouse_id'=>2
        ]);

        ZeroFrige::create([

            'warehouse_id'=>3
        ]);

        ZeroFrige::create([

            'warehouse_id'=>4
        ]);

        ZeroFrige::create([

            'warehouse_id'=>5
        ]);

       
    }
}
