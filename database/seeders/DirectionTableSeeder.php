<?php

namespace Database\Seeders;

use App\Models\Direction;
use Illuminate\Database\Seeder;

class DirectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Direction::create([

            'section'=>'تصنيع',
            'to'=>'صاعقة 1'
        ]);

        Direction::create([

            'section'=>'تصنيع',
            'to'=>'صاعقة 2'
        ]);

        Direction::create([

            'section'=>'تصنيع',
            'to'=>'صاعقة 3'
        ]);

        Direction::create([

            'section'=>'تصنيع',
            'to'=>'براد صفري'
        ]);

        Direction::create([

            'section'=>'التقطيع',
            'to'=>'تصنيع'
        ]);

        Direction::create([

            'section'=>'التقطيع',
            'to'=>'براد صفري'
        ]);

        Direction::create([

            'section'=>'بحرات',
            'to'=>'صاعقة 1'
        ]);

        Direction::create([

            'section'=>'بحرات',
            'to'=>'صاعقة 2'
        ]);

        Direction::create([

            'section'=>'بحرات',
            'to'=>'صاعقة 3'
        ]);

        Direction::create([

            'section'=>'بحرات',
            'to'=>'براد صفري'
        ]);

        Direction::create([

            'section'=>'بحرات',
            'to'=>'التقطيع'
        ]);

        Direction::create([

            'section'=>'بحرات',
            'to'=>'تصنيع'
        ]);

        Direction::create([

            'section'=>'صاعقة 1',
            'to'=>'تخزين'
        ]);

        Direction::create([

            'section'=>'صاعقة 2',
            'to'=>'تخزين'
        ]);

        Direction::create([

            'section'=>'صاعقة 3',
            'to'=>'تخزين'
        ]);

        Direction::create([

            'section'=>'براد صفري',
            'to'=>'بحرات'
        ]);

        Direction::create([

            'section'=>'براد صفري',
            'to'=>'التقطيع'
        ]);

        Direction::create([

            'section'=>'براد صفري',
            'to'=>'تصنيع'
        ]);

    }
}
