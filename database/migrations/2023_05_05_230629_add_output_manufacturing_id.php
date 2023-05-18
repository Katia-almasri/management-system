<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutputManufacturingId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('input_manufacturings', function (Blueprint $table) {
            $table->unsignedBigInteger('output_manufacturing_id')->nullable();
            $table->foreign('output_manufacturing_id')->references('id')->on('output_manufacturings')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
