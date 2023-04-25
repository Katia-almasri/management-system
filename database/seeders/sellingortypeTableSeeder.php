<?php

namespace Database\Seeders;

use App\Models\sellingortype;
use Illuminate\Database\Seeder;

class sellingortypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        sellingortype::create([
            'name'=>'مطعم'
        ]);

        sellingortype::create([
            'name'=>'فندق ومطعم'
        ]);

        sellingortype::create([
            'name'=>'فندق'
        ]);

        sellingortype::create([
            'name'=>'محل بيع خاص'
        ]);
    }
}
