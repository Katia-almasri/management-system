<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetWeightToPoultryReceiptDetectionsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poultry_receipt_detections_details', function (Blueprint $table) {
            $table->integer('net_weight')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poultry_receipt_detections_details', function (Blueprint $table) {
            //
        });
    }
}
