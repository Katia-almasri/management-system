<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcceptBySalesMangerToRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_purchasing_requests', function (Blueprint $table) {
            $table->boolean('accept_by_sales')->nullable()->after('accept_by_ceo');
            $table->boolean('is_seen')->nullable()->change();
            $table->boolean('command')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request', function (Blueprint $table) {
            //
        });
    }
}
