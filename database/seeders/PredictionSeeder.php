<?php

namespace Database\Seeders;

use App\Models\Prediction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PredictionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $date = Carbon::now();
        $nextMonth = $date->copy()->addMonth(); // Add one month to the current date
        $formattedDate = $nextMonth->format('Y-m-d');

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 890.68,
            'output_type' => 'صدر بجلدة'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 1700.64,
            'output_type' => 'شرحة'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 838.02,
            'output_type' => 'وردة'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 52.79,
            'output_type' => 'جناح'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 1153.61,
            'output_type' => 'فخذ كامل'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 478.72,
            'output_type' => 'فروج نيء'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 1390.75,
            'output_type' => 'سودة'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 800.13,
            'output_type' => 'حواصل'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 1349.04,
            'output_type' => 'دجاج بياض لحم'

        ]);

        Prediction::create([
            'year_month' => $formattedDate,
            'expected_weight' => 1063.36,
            'output_type' => 'مجروم فرنسي'

        ]);

    }
}