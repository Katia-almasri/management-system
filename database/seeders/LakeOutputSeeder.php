<?php

namespace Database\Seeders;

use App\Models\LakeOutput;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LakeOutputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LakeOutput::create([

            'output_date'=>Carbon::now(),
            'weight'=>200,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>1,
            'lake_id'=>1,
            'output_to'=>'بحرات',
        ]);

        LakeOutput::create([

            'output_date'=>Carbon::now(),
            'weight'=>200,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>1,
            'lake_id'=>1,
            'output_to'=>'بحرات',
        ]);

        LakeOutput::create([

            'output_date'=>Carbon::now(),
            'weight'=>200,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>1,
            'lake_id'=>1,
            'output_to'=>'بحرات',
        ]);
    }
}
