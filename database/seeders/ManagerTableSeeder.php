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
        //1
        Manager::create([
            'managing_level'=>'Purchasing-and-Sales-manager',
            'first_name'=>'katia',
            'last_name'=>'almasri',
            'username'=>'katia almasri',
            'password'=>Hash::make('password'),
        ]);
        //2
        Manager::create([
            'managing_level'=>'ceo',
            'first_name'=>'dani',
            'last_name'=>'almasri',
            'username'=>'dani almasri',
            'password'=>Hash::make('password'),
        ]);
        //3
        Manager::create([
            'managing_level'=>'Mechanism-Coordinator',
            'first_name'=>'ahmed',
            'last_name'=>'ahmed',
            'username'=>'ahmed ahmed',
            'password'=>Hash::make('password'),
        ]);
        //4
        Manager::create([
            'managing_level'=>'Production_Manager',
            'first_name'=>'sami',
            'last_name'=>'sami',
            'username'=>'sami',
            'password'=>Hash::make('password'),
        ]);
        //5
        Manager::create([
            'managing_level'=>'libra-commander',
            'first_name'=>'salem',
            'last_name'=>'salem',
            'username'=>'salem salem',
            'password'=>Hash::make('password'),
        ]);
        //6
        Manager::create([
            'managing_level'=>'Accounting-Manager',
            'first_name'=>'rami',
            'last_name'=>'rami',
            'username'=>'rami rami',
            'password'=>Hash::make('password'),
        ]);

        Manager::create([
            'managing_level'=>'libra-commander',
            'first_name'=>'salem',
            'last_name'=>'salem',
            'username'=>'salem salem',
            'password'=>Hash::make('password'),
        ]);

    }
}
