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

            'name'=>' مارسيدس ',
            'model'=>'benz',
            'storage_capacity'=> 2000,
            'truck_number' =>123456,
            'governorate_name' => 'دمشق',
            'mashenism_coordinator_id' => 3,
            'state' => 'متاحة'
        ]);

        Truck::create([

            'name'=>'سوزوكي ',
            'model'=>'سوزوكي',
            'storage_capacity'=> 100,
            'truck_number' =>12345,
            'governorate_name' => 'دمشق',
            'mashenism_coordinator_id' => 3,
            'state' => 'متاحة'
        ]);
    }
}
