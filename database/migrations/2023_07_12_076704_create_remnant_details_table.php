<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemnantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remnant_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('remnant_id')->nullable();
            $table->foreign('remnant_id')->references('id')->on('remnant_warehouses')->onDelete('cascade');
            
            $table->float('weight')->nullable();
            $table->float('cur_weight')->nullable();
            $table->float('cur_output_weight')->default(0);
            $table->string('input_from');
            //morph
            $table->morphs('inputable');
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
        Schema::dropIfExists('remnant_details');
    }
}
