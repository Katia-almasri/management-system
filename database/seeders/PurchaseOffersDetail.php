<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPurchaseOffer;

class PurchaseOffersDetail extends Seeder
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
            'amount'=>500,
            'type'=>'جاج احمر',
        ]);

        DetailPurchaseOffer::create([
            'purchase_offers_id'=>1,
            'amount'=>500,
            'type'=>'جاج بلدي',
        ]);
    }
}
