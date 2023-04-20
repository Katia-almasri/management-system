<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoultryReceiptDetectionsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poultry_receipt_detections_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('receipt_id');
            $table->foreign('receipt_id')->references('id')->on('poultry_receipt_detections')->onDelete('cascade');

            $table->unsignedBigInteger('row_material_id');
            $table->foreign('row_material_id')->references('id')->on('row_materials')->onDelete('cascade');
            
            $table->integer('num_cages');
            $table->integer('tot_weight')->nullable();
            $table->integer('num_birds')->default(10);
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
        Schema::dropIfExists('poultry_receipt_detections_details');
    }
}
