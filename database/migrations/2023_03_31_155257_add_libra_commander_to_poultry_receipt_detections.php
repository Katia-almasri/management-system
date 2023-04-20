<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLibraCommanderToPoultryReceiptDetections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('poultry_receipt_detections', function (Blueprint $table) {
            $table->unsignedBigInteger('libra_commander_id')->after('farm_id');
            $table->foreign('libra_commander_id')->references('id')->on('managers')->onDelete('cascade');

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
