<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemnatDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remnat_details', function (Blueprint $table) {
            $table->id();
            $table->integer('weight');
            $table->unsignedBigInteger('output_remnat_det_id');
            $table->foreign('output_remnat_det_id')->references('id')->on('output_remnat_details')->onDelete('cascade');
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
        Schema::dropIfExists('remnat_details');
    }
}
