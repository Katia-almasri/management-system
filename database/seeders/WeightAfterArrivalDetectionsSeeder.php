<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeightAfterArrivalRequest;

class WeightAfterArrivalDetectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeightAfterArrivalRequest::create([

            'libra_commander_id'=>5,
            'polutry_detection_id '=>1,
            'dead_chicken'=> 11,
            'tot_weight_after_arrival' =>5210.0,
            'weight_loss' => 8398.0,
            'net_weight_after_arrival' => 5090.0,

        ]);

    }
}
