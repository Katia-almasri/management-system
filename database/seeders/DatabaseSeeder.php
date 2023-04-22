<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(ManagerTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(DriverTableSeeder::class);
        $this->call(TruckTableSeeder::class);
        $this->call(FarmTableSeeder::class);
        $this->call(SellingPointTableSeeder::class);
        $this->call(SalesPurchasingRequestsTableSeeder::class);
        $this->call(SalesPurchasingRequestsDetailsTableSeeder::class);
        $this->call(PurchaseOffers::class);
        $this->call(PurchaseOffersDetail::class);
        $this->call(TripsTableSeeder::class);

    }
}
