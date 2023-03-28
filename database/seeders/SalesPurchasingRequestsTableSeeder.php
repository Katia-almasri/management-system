<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\salesPurchasingRequset;

class SalesPurchasingRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        salesPurchasingRequset::create([
            'ceo_id'=>2,
            'purchasing_manager_id'=>1,
            'farm_id'=>1,
            'total_amount'=>100,
            'request_type'=>0
        ]);

        salesPurchasingRequset::create([
            'ceo_id'=>2,
            'purchasing_manager_id'=>1,
            'selling_port_id'=>1,
            'total_amount'=>100,
            'request_type'=>1
        ]);
    }
}
