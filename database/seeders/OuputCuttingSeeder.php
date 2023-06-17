<?php

namespace Database\Seeders;

use App\Models\output_cutting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OuputCuttingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        output_cutting::create([
            'production_date'=>Carbon::now()
        ]);

        output_cutting::create([
            'production_date'=>Carbon::now()
        ]);

        output_cutting::create([
            'production_date'=>Carbon::now()
        ]);
    }
}
