<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightAfterArrivalDetectionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('after_arrival_detection_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('detection_id');
            $table->foreign('detection_id')->references('id')->on('weight_after_arrival_detections')->onDelete('cascade');

            $table->unsignedBigInteger('details_id');
            $table->foreign('details_id')->references('id')->on('poultry_receipt_detections_details')->onDelete('cascade');

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
        Schema::dropIfExists('weight_after_arrival_detection_details');
    }
}
