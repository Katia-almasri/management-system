<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\salesPurchasingRequsetDetail;

class SalesPurchasingRequestsDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        salesPurchasingRequsetDetail::create([
            'requset_id'=>1,
            'amount'=>50,
            'type'=>'شرحات',
        ]);

        salesPurchasingRequsetDetail::create([
            'requset_id'=>1,
            'amount'=>50,
            'type'=>'وردة',
        ]);
    }
}
