<?php

namespace Database\Seeders;

use App\Models\OutputManufacturing;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OuputManufactoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OutputManufacturing::create([
            'production_date'=>Carbon::now()
        ]);

        OutputManufacturing::create([
            'production_date'=>Carbon::now()
        ]);

        OutputManufacturing::create([
            'production_date'=>Carbon::now()
        ]);
    }
}
