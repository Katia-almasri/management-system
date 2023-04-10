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
            'state'=>'دوام',

        ]);


        Driver::create([
            'name'=>'علي',
            'state'=>'دوام',

        ]);
    }
}
