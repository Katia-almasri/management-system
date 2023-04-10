<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SellingPort;

class SellingPointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SellingPort::create([
            'owner'=>'فندق الشام',
            'location'=>'دمشق',
            'mobile_number'=>12323484788,
            'name' => 'فندق الشام',
            'type' => 'مطعم',
            'username' =>'فندق الشام',
            'password' => 'password'
        ]);


        SellingPort::create([
            'owner'=>'محلات المصري',
            'location'=>'جرمانا',
            'mobile_number'=>2344323443,
            'name' => 'محلات المصري',
            'type' => 'مطعم',
            'username' =>'محلات المصري',
            'password' => 'password'
        ]);
    }
}
