<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcceptToSellingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selling_orders', function (Blueprint $table) {
            //
            $table->boolean('accept_by_sales_manager')->after('sellingPort_id');
            $table->boolean('accept_by_ceo')->after('accept_by_sales_manager');
            $table->dropColumn('accept');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selling_orders', function (Blueprint $table) {
            //
        });
    }
}
