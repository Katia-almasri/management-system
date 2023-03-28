<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;

class FarmTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { Farm::create([
            'owner'=>'محمود',
            'location'=>'مليحة',
            'mobile_number'=>12323484788
        ]);
       

        
        Farm::create([
            'owner'=>'مرعي',
            'location'=>'جرمانا',
            'mobile_number'=>2344323443
        ]);
    }
}
