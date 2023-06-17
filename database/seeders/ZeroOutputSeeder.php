<?php

namespace Database\Seeders;

use App\Models\ZeroFrigeOutput;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ZeroOutputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZeroFrigeOutput::create([

            'output_date'=>Carbon::now(),
            'weight'=>200,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>1,
            'zero_id'=>1,
            'output_to'=>'بحرات',
        ]);

        ZeroFrigeOutput::create([

            'output_date'=>Carbon::now(),
            'weight'=>200,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>2,
            'zero_id'=>2,
            'output_to'=>'بحرات',
        ]);

        ZeroFrigeOutput::create([

            'output_date'=>Carbon::now(),
            'weight'=>200,
            'outputable_type'=>'App\Models\LakeDetail',
            'outputable_id'=>3,
            'zero_id'=>3,
            'output_to'=>'بحرات',
        ]);

    }
}
