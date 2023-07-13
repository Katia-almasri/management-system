<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreateOutPutRemnatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('output_remnat_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_remant_id');
            $table->foreign('type_remant_id')->references('id')->on('remnants_types')->onDelete('cascade');
            $table->integer('weight');
            $table->unsignedBigInteger('output_slaughter_id')->nullable();
            $table->foreign('output_slaughter_id')->references('id')->on('output_slaughtersupervisors')->onDelete('cascade');
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
        Schema::dropIfExists('create_out_put_remnat_details');
    }
}
