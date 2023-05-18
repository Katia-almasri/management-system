<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDet1DetailToDet1OutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detonator_frige1_outputs', function (Blueprint $table) {
            $table->unsignedBigInteger('det1_id')->nullable();
            $table->foreign('det1_id')->references('id')->on('detonator_frige1s')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('det1_outputs', function (Blueprint $table) {
            //
        });
    }
}
