<?php

namespace Database\Seeders;

use App\Models\OutputManufacturingDetails;
use Illuminate\Database\Seeder;

class OuputManufactoringDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OutputManufacturingDetails::create([
            'output_manufacturing_id'=>1,
            'weight'=>200,
            'type_id'=>1,
            'outputable_type'=>'App\Models\ZeroFrigeDetail',
            'outputable_id'=>1,
        ]);

        OutputManufacturingDetails::create([
            'output_manufacturing_id'=>1,
            'weight'=>200,
            'type_id'=>1,
            'outputable_type'=>'App\Models\ZeroFrigeDetail',
            'outputable_id'=>2,
        ]);

    }
}
