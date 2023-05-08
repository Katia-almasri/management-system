<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\outPut_SlaughterSupervisorType_table;
class PoultryReceiptDetectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        outPut_SlaughterSupervisorType_table::create([
            'type'=>"جاج أحمر مذبوح",
            'by_section'=>"قسم الذبح",
            'number_day_validity'=>10
        ]);

        outPut_SlaughterSupervisorType_table::create([
            'type'=>"جاج أبيض مذبوح",
            'by_section'=>"قسم الذبح",
            'number_day_validity'=>10
        ]);

        outPut_SlaughterSupervisorType_table::create([
            'type'=>"شرحات",
            'by_section'=>"قسم التقطيع",
            'number_day_validity'=>10
        ]);

        outPut_SlaughterSupervisorType_table::create([
            'type'=>"وردة",
            'by_section'=>"قسم التقطيع",
            'number_day_validity'=>10
        ]);

        outPut_SlaughterSupervisorType_table::create([
            'type'=>"جاج متبل",
            'by_section'=>"قسم التصنيع",
            'number_day_validity'=>10
        ]);
    }
}
