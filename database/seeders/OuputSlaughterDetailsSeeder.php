<?php

namespace Database\Seeders;

use App\Models\outPut_SlaughterSupervisor_detail;
use Illuminate\Database\Seeder;

class OuputSlaughterDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        outPut_SlaughterSupervisor_detail::create([
            'weight'=>200,
            'type_id'=>1,
            'output_id'=>1
        ]);

        outPut_SlaughterSupervisor_detail::create([
            'weight'=>200,
            'type_id'=>2,
            'output_id'=>1
        ]);

        outPut_SlaughterSupervisor_detail::create([
            'weight'=>200,
            'type_id'=>3,
            'output_id'=>1
        ]);

    }
}
