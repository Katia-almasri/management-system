<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RowMaterial;

class RowMaterialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RowMaterial::create([
            'name'=>'فروج أحمر'
        ]);

        RowMaterial::create([
            'name'=>'فروج أم'
        ]);

        RowMaterial::create([
            'name'=>'فروج فرنسي'
        ]);

        RowMaterial::create([
            'name'=>'فروج بلدي'
        ]);
    }
}
