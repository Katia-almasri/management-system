<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;
use Illuminate\Support\Facades\Hash;

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
            'mobile_number'=>12323484788,
            'name' => 'مزرعة دواجن',
            'username' => 'مزرعة دواجن',
            'password'=>Hash::make('password'),
            'approved_at' => '2023-04-01 01:53:04'
        ]);



        Farm::create([
            'owner'=>'مرعي',
            'location'=>'جرمانا',
            'mobile_number'=>2344323443,
            'name' => '1مزرعة دواجن',
            'username' => '1مزرعة دواجن',
            'password'=>Hash::make('password'),
            'approved_at' => '2023-04-01 01:53:04'
        ]);
    }
}
