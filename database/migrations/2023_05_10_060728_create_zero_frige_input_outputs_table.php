<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZeroFrigeInputOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zero_frige_input_outputs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('output_id')->nullable();
            $table->foreign('output_id')->references('id')->on('zero_frige_outputs')->onDelete('cascade');

            $table->unsignedBigInteger('input_id')->nullable();
            $table->foreign('input_id')->references('id')->on('zero_frige_details')->onDelete('cascade');

            $table->float('weight')->nullable();
            $table->integer('amount')->nullable();

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
        Schema::dropIfExists('zero_frige_input_outputs');
    }
}
