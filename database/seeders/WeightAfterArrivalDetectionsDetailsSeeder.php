<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeightAfterArrivalRequest;

class WeightAfterArrivalDetectionsDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeightAfterArrivalRequest::create([

            'detection_id '=>1,
            'details_id  '=>1,
            'dead_chicken'=> 5,
            'tot_weight_after_arrival' =>3570.0,
            'weight_loss' => 630.0,
            'net_weight_after_arrival' => 2820.0,
            'current_weight' => 2820.0,
            'approved_at' => 1,
        ]);
        WeightAfterArrivalRequest::create([

            'detection_id '=>1,
            'details_id  '=>2,
            'dead_chicken'=> 3,
            'tot_weight_after_arrival' =>500.0,
            'weight_loss' => 4218.0,
            'net_weight_after_arrival' => -3838.0,
            'current_weight' => 2820.0,
            'approved_at' => null,
        ]);

    }
}
