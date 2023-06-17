<?php

namespace Database\Seeders;

use App\Models\outPut_SlaughterSupervisor_table;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OuputSlaughterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        outPut_SlaughterSupervisor_table::create([
            'production_date'=>Carbon::now()
        ]);

        outPut_SlaughterSupervisor_table::create([
            'production_date'=>Carbon::now()
        ]);


        outPut_SlaughterSupervisor_table::create([
            'production_date'=>Carbon::now()
        ]);

    }
}
