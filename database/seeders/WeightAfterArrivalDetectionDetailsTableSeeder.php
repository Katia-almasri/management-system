<?php

namespace Database\Seeders;

use App\Models\weightAfterArrivalDetectionDetail;
use Illuminate\Database\Seeder;

class WeightAfterArrivalDetectionDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        weightAfterArrivalDetectionDetail::create([
            'detection_id'=>1,
            'details_id'=>1,
            'dead_chicken'=>5,
            'tot_weight_after_arrival'=>3570.00,
            'weight_loss'=>3570.00,
            'net_weight_after_arrival'=>2820.00,
            'current_weight'=>2820.00,
            'approved_at'=>null

        ]);

        weightAfterArrivalDetectionDetail::create([
            'detection_id'=>1,
            'details_id'=>2,
            'dead_chicken'=>5,
            'tot_weight_after_arrival'=>3570.00,
            'weight_loss'=>3570.00,
            'net_weight_after_arrival'=>2820.00,
            'current_weight'=>2820.00,
            'approved_at'=>null

        ]);

        weightAfterArrivalDetectionDetail::create([
            'detection_id'=>1,
            'details_id'=>3,
            'dead_chicken'=>5,
            'tot_weight_after_arrival'=>3570.00,
            'weight_loss'=>3570.00,
            'net_weight_after_arrival'=>2820.00,
            'current_weight'=>2820.00,
            'approved_at'=>null

        ]);

        weightAfterArrivalDetectionDetail::create([
            'detection_id'=>1,
            'details_id'=>1,
            'dead_chicken'=>5,
            'tot_weight_after_arrival'=>3570.00,
            'weight_loss'=>3570.00,
            'net_weight_after_arrival'=>2820.00,
            'current_weight'=>2820.00,
            'approved_at'=>null

        ]);

        weightAfterArrivalDetectionDetail::create([
            'detection_id'=>1,
            'details_id'=>2,
            'dead_chicken'=>5,
            'tot_weight_after_arrival'=>3570.00,
            'weight_loss'=>3570.00,
            'net_weight_after_arrival'=>2820.00,
            'current_weight'=>2820.00,
            'approved_at'=>null

        ]);

        weightAfterArrivalDetectionDetail::create([
            'detection_id'=>1,
            'details_id'=>3,
            'dead_chicken'=>5,
            'tot_weight_after_arrival'=>3570.00,
            'weight_loss'=>3570.00,
            'net_weight_after_arrival'=>2820.00,
            'current_weight'=>2820.00,
            'approved_at'=>null

        ]);

    }
}
