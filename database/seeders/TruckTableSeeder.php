<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Truck;

class TruckTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Truck::create([
            'mashenism_coordinator_id'=>3,
            'name'=>' مارسيدس ',
            'model'=>'benz',
            'storage_capacity'=> 2000
        ]);

        Truck::create([
            'mashenism_coordinator_id'=>3,
            'name'=>'سوزوكي ',
            'model'=>'سوزوكي',
            'storage_capacity'=> 100
        ]);
    }
}
