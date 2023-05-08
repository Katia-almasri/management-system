<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreateOutputCuttingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('output_cutting_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('output_cutting_id');
            $table->foreign('output_cutting_id')->references('id')->on('output_cuttings')->onDelete('cascade');
            $table->integer('weight');
            $table->timestamp('expiry_date')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('output_production_types')->onDelete('cascade');
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
        Schema::dropIfExists('create_output_cutting_details');
    }
}
