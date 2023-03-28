<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manager;
use Illuminate\Support\Facades\Hash;


class ManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manager::create([
            'managing_level'=>'Purchasing-and-Sales-manager',
            'first_name'=>'katia',
            'last_name'=>'almasri',
            'username'=>'katia almasri',
            'password'=>Hash::make('password'),
        ]);

        Manager::create([
            'managing_level'=>'ceo',
            'first_name'=>'dani',
            'last_name'=>'almasri',
            'username'=>'dani almasri',
            'password'=>Hash::make('password'),
        ]);

        Manager::create([
            'managing_level'=>'Mechanism-Coordinator',
            'first_name'=>'ahmed',
            'last_name'=>'ahmed',
            'username'=>'ahmed ahmed',
            'password'=>Hash::make('password'),
        ]);

    }
}
