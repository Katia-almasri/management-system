<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmptyWeightToWeightAfterArrivalDetections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weight_after_arrival_detections', function (Blueprint $table) {
            $table->float('empty_weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weight_after_arrival_detections', function (Blueprint $table) {
            //
        });
    }
}
