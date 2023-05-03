<?php

namespace Database\Seeders;

use App\Models\PoultryReceiptDetectionsDetails;
use Illuminate\Database\Seeder;

class PoultryRecieptDetectionDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PoultryReceiptDetectionsDetails::create([
            'receipt_id'=>1,
            'row_material_id'=>1,
            'num_cages'=>3,
            'tot_weight'=>4200,
            'num_birds'=>30,
            'net_weight'=>4080,

        ]);

        PoultryReceiptDetectionsDetails::create([
            'receipt_id'=>1,
            'row_material_id'=>2,
            'num_cages'=>3,
            'tot_weight'=>4718,
            'num_birds'=>30,
            'net_weight'=>4598,

        ]);

        PoultryReceiptDetectionsDetails::create([
            'receipt_id'=>1,
            'row_material_id'=>3,
            'num_cages'=>3,
            'tot_weight'=>4690,
            'num_birds'=>30,
            'net_weight'=>4570,

        ]);

        PoultryReceiptDetectionsDetails::create([
            'receipt_id'=>1,
            'row_material_id'=>1,
            'num_cages'=>3,
            'tot_weight'=>4200,
            'num_birds'=>30,
            'net_weight'=>4080,

        ]);

        PoultryReceiptDetectionsDetails::create([
            'receipt_id'=>1,
            'row_material_id'=>2,
            'num_cages'=>3,
            'tot_weight'=>4718,
            'num_birds'=>30,
            'net_weight'=>4598,

        ]);

        PoultryReceiptDetectionsDetails::create([
            'receipt_id'=>1,
            'row_material_id'=>3,
            'num_cages'=>3,
            'tot_weight'=>4690,
            'num_birds'=>30,
            'net_weight'=>4570,

        ]);

        
    }
}
