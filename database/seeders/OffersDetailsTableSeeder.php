<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPurchaseOffer;
class OffersDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetailPurchaseOffer::create([
            'purchase_offers_id'=>1,
            'type'=>'دجاج فرنسي',
            'amount'=>900
        ]);

        DetailPurchaseOffer::create([
            'purchase_offers_id'=>1,
            'type'=>'فروج أحمر',
            'amount'=>300
        ]);

        DetailPurchaseOffer::create([
            'purchase_offers_id'=>1,
            'type'=>'فروج بلدي',
            'amount'=>600
        ]);
    }
}
