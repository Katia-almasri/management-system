<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseOffer;
class OffersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PurchaseOffer::create([
            'farm_id'=>1,
            'total_amount'=>1800
        ]);

        PurchaseOffer::create([
            'farm_id'=>1,
            'total_amount'=>1400
        ]);
    }
}
