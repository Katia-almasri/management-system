<?php

namespace Database\Seeders;

use App\Models\ZeroFrigeDetail;
use Illuminate\Database\Seeder;

class ZeroDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZeroFrigeDetail::create([

            'zero_frige_id'=>1,
            'weight'=>200,
            'cur_weight'=>200,
            'inputable_type'=>'App\Models\LakeOutput',
            'inputable_id'=>1,
            'input_from'=>'مستودع البحرات',
        ]);
        
        ZeroFrigeDetail::create([

            'zero_frige_id'=>2,
            'weight'=>200,
            'cur_weight'=>200,
            'inputable_type'=>'App\Models\LakeOutput',
            'inputable_id'=>2,
            'input_from'=>'مستودع البحرات',
        ]);

        ZeroFrigeDetail::create([

            'zero_frige_id'=>3,
            'weight'=>200,
            'cur_weight'=>200,
            'inputable_type'=>'App\Models\LakeOutput',
            'inputable_id'=>3,
            'input_from'=>'مستودع البحرات',
        ]);

    }
}
