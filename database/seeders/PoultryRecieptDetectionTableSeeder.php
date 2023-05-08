<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PoultryReceiptDetection;
class PoultryRecieptDetectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PoultryReceiptDetection::create([
             'farm_id'=>2,
             'libra_commander_id'=>5,
             'tot_weight'=>13608.00,
             'empty'=>360.00,
             'net_weight'=>13248.00,
             'num_cages'=>9,
             'is_weighted_after_arrive'=>1,
             'is_seen_by_sales_manager'=>0
        ]);

        PoultryReceiptDetection::create([
            'farm_id'=>2,
            'libra_commander_id'=>5,
            'tot_weight'=>13608.00,
            'empty'=>360.00,
            'net_weight'=>13248.00,
            'num_cages'=>9,
            'is_weighted_after_arrive'=>1,
            'is_seen_by_sales_manager'=>0
       ]);


    }
}
