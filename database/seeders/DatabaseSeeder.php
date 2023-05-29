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
        $this->call(RowMaterialTableSeeder::class);
        $this->call(productTableSeeder::class);
        $this->call(sellingortypeTableSeeder::class);
        $this->call(ProductionTypes::class);
        $this->call(PoultryRecieptDetectionTableSeeder::class);
        $this->call(PoultryRecieptDetectionDetailsTableSeeder::class);
        $this->call(WeightAfterArrivalDetectionTableSeeder::class);
        $this->call(WarehouseTableSeeder::class);
        $this->call(LakeTableSeeder::class);
        $this->call(ZeroTableSeeder::class);
        $this->call(Det1TableSeeder::class);
        $this->call(Det2TableSeeder::class);
        $this->call(Det3TableSeeder::class);
        $this->call(StoreTableSeeder::class);
        $this->call(DirectionTableSeeder::class);
        
        //today
        $this->call(WarehouseTypesTableSeed::class);
        
        
        
        



    }
}
