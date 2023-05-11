<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Driver::create([
            'name'=>'سعيد',
            'state'=>'متاح',
            'address'=>'جرمانا-ساحة الرئيس',
            'mobile_number'=>'0912334343',
            'mashenism_coordinator_id' => 3

        ]);


        Driver::create([
            'name'=>'علي',
            'state'=>'دوام',
            'address'=>'جرمانا-ساحة الرئيس',
            'mobile_number'=>'0912334343',

            'mashenism_coordinator_id' => 3
        ]);
    }
}
