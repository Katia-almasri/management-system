<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSeenByToSalesPurchasingRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_purchasing_requests', function (Blueprint $table) {
            $table->boolean('is_seen_by_mechanism_coordinator')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_purchasing_requests', function (Blueprint $table) {
            //
        });
    }
}
