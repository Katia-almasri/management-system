<?php

namespace Database\Seeders;

use App\Models\output_cutting_detail;
use Illuminate\Database\Seeder;

class OuputCuttingDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        output_cutting_detail::create([
            'output_cutting_id'=>1,
            'weight'=>200,
            'type_id'=>1,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>1,
        ]);

        output_cutting_detail::create([
            'output_cutting_id'=>1,
            'weight'=>200,
            'type_id'=>1,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>2,
        ]);

    }
}
