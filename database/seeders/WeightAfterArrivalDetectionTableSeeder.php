<?php

namespace Database\Seeders;

use App\Models\weightAfterArrivalDetection;
use Illuminate\Database\Seeder;

class WeightAfterArrivalDetectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        weightAfterArrivalDetection::create([
            'libra_commander_id'=>5,
            'polutry_detection_id'=>1,
            'dead_chicken'=>11,
            'tot_weight_after_arrival'=>5210.00,
            'weight_loss'=>8398.00,
            'net_weight_after_arrival'=>8398.00,

        ]);

        weightAfterArrivalDetection::create([
            'libra_commander_id'=>5,
            'polutry_detection_id'=>1,
            'dead_chicken'=>11,
            'tot_weight_after_arrival'=>5210.00,
            'weight_loss'=>8398.00,
            'net_weight_after_arrival'=>8398.00,

        ]);
    }
}
