<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cage_details', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('details_id');
            $table->foreign('details_id')->references('id')->on('poultry_receipt_detections_details')->onDelete('cascade');

            $table->float('cage_weight');
            $table->integer('num_birds');

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
        Schema::dropIfExists('cage_details');
    }
}
