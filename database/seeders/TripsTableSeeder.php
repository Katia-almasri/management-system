<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;

class TripsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Trip::create([
            'truck_id'=>1,
            'manager_id'=>3,
            'driver_id'=>1,
            'sales_purchasing_requsets_id'=>1,
            'farm_id'=>1,
        ]);

        Trip::create([
            'truck_id'=>2,
            'manager_id'=>3,
            'driver_id'=>2,
            'sales_purchasing_requsets_id'=>2,
            'selling_port_id'=>2,
        ]);
    }
}
