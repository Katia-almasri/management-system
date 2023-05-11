<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PoultryReceiptDetection;
class PoultryReceiptDetectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PoultryReceiptDetection::create([
            'farm_id'=>1,
            'libra_commander_id'=>5,
            'tot_weight'=>13608,
            'empty'=>360,
            'net_weight'=>13248,
            'num_cages'=>9,
            'is_weighted_after_arrive'=>0,
        ]);

        PoultryReceiptDetection::create([
            'farm_id'=>1,
            'libra_commander_id'=>5,
            'tot_weight'=>13608,
            'empty'=>360,
            'net_weight'=>13248,
            'num_cages'=>9,
            'is_weighted_after_arrive'=>1,
        ]);
    }
}
