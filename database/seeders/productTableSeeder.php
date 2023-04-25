<?php

namespace Database\Seeders;

use App\Models\product;
use Illuminate\Database\Seeder;

class productTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        product::create([
            'name'=>'سودة'   
        ]);

        product::create([
            'name'=>'دجاج مقطع'   
        ]);

        product::create([
            'name'=>'اسكالوب'   
        ]);

        product::create([
            'name'=>'جوانح'   
        ]);

        product::create([
            'name'=>'ريش'   
        ]);
    }
}
