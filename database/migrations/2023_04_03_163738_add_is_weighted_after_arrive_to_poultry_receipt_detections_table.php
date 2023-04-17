<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsWeightedAfterArriveToPoultryReceiptDetectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poultry_receipt_detections', function (Blueprint $table) {
            $table->boolean('is_weighted_after_arrive')->after('num_cages')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poultry_receipt_detections', function (Blueprint $table) {
            //
        });
    }
}
