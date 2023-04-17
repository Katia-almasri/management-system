<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightAfterArrivalDetectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_after_arrival_detections', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('libra_commander_id');
            $table->foreign('libra_commander_id')->references('id')->on('managers')->onDelete('cascade');

            $table->unsignedBigInteger('polutry_detection_id');
            $table->foreign('polutry_detection_id')->references('id')->on('poultry_receipt_detections')->onDelete('cascade');

            $table->integer('dead_chicken')->default(0);
            $table->float('tot_weight_after_arrival');
            $table->float('weight_loss')->default(0.0);
            $table->float('net_weight_after_arrival')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weight_after_arrival_detections');
    }
}
