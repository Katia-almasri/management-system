<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDet3DetailToDet3OutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detonator_frige3_outputs', function (Blueprint $table) {
            $table->unsignedBigInteger('det3_id')->nullable();
            $table->foreign('det3_id')->references('id')->on('detonator_frige3s')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('det3_outputs', function (Blueprint $table) {
            //
        });
    }
}
