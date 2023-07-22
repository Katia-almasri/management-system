<?php

namespace Database\Seeders;

use App\Models\RemnantsType;
use Illuminate\Database\Seeder;

class remnantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RemnantsType::create([
            'name'=>'ريش صغير',
            'by_section'=>'قسم الذبح'
        ]);

        RemnantsType::create([
            'name'=>'ريش طويل',
            'by_section'=>'قسم الذبح'
        ]);

        RemnantsType::create([
            'name'=>'مخلفات قوانص',
            'by_section'=>'قسم التقطيع'
        ]);

        RemnantsType::create([
            'name'=>'مخلفات متنوعة',
            'by_section'=>'قسم التصنيع'
        ]);
    }
}
