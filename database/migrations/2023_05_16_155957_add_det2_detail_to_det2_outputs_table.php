<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDet2DetailToDet2OutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detonator_frige2_outputs', function (Blueprint $table) {
            $table->unsignedBigInteger('det2_id')->nullable();
            $table->foreign('det2_id')->references('id')->on('detonator_frige2s')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('det2_outputs', function (Blueprint $table) {
            //
        });
    }
}
