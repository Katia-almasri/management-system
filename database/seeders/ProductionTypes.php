<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\outPut_Type_Production;
class ProductionTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1
        outPut_Type_Production::create([
            'type'=>"جاج أحمر مذبوح",
            'by_section'=>"قسم الذبح"
            ]);
        //2
        outPut_Type_Production::create([
            'type'=>"جاج أبيض مذبوح",
            'by_section'=>"قسم الذبح"
        ]);
        //3
        outPut_Type_Production::create([
            'type'=>"شرحات",
            'by_section'=>"قسم التقطيع"
        ]);
        //4
        outPut_Type_Production::create([
            'type'=>"وردة",
            'by_section'=>"قسم التقطيع"
        ]);
        //5
        outPut_Type_Production::create([
            'type'=>"جاج متبل",
            'by_section'=>"قسم التصنيع"
        ]);
    }
}
