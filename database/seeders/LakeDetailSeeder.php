<?php

namespace Database\Seeders;

use App\Models\LakeDetail;
use Illuminate\Database\Seeder;

class LakeDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LakeDetail::create([

            'lake_id'=>1,
            'weight'=>200,
            'cur_weight'=>200,
            'inputable_type'=>'App\Models\outPut_SlaughterSupervisor_table',
            'inputable_id'=>1,
            'input_from'=>'الذبح',
        ]);

        LakeDetail::create([

            'lake_id'=>2,
            'weight'=>200,
            'cur_weight'=>200,
            'inputable_type'=>'App\Models\outPut_SlaughterSupervisor_table',
            'inputable_id'=>2,
            'input_from'=>'الذبح',
        ]);


        LakeDetail::create([

            'lake_id'=>3,
            'weight'=>200,
            'cur_weight'=>200,
            'inputable_type'=>'App\Models\outPut_SlaughterSupervisor_table',
            'inputable_id'=>3,
            'input_from'=>'الذبح',
        ]);

    }
}
